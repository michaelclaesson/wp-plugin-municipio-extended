<?php

use Elastic\Elasticsearch\ClientBuilder;
use ElasticPress\Utils;

add_filter("Municipio/Hook/searchFormValidation", "__return_false");

add_filter("ep_skip_query_integration", "__return_true");

function mx_search_perform_es_search($body) {
  $host = Utils\get_host();
  if (empty($host)) {
    throw new Exception("No Elasticsearch host defined.");
  }
  $hosts = [$host];
  mx_error_log("Searching on hosts", $hosts);
  $index = \ElasticPress\Indexables::factory()
    ->get("post")
    ->get_index_name(null);
  if (empty($index)) {
    throw new Exception("No Elasticsearch index defined.");
  }
  mx_error_log("Searching in index '$index'");

  $client = ClientBuilder::create()->setHosts($hosts)->build();

  return $client->search([
    "index" => $index,
    "body" => $body,
  ]);
}

function mx_search_ajax_handler() {
  check_ajax_referer("mx_search", "nonce");

  $default_boosted_post_type_weight = 2;
  $post_types = mx_get_regular_post_types();
  $post_types[] = "attachment";
  $mx_search_settings_post_types =
    get_field("mx_search_settings_post_types", "option") ?: [];

  $data = json_decode(json_decode('"' . $_POST["data"] . '"'), true);

  // $fieldsToHighlight = [
  //   'post_title',
  //   'post_content_filtered',
  // ];

  $fields = [
    "post_title^10",
    "attachments.attachment.title^10",
    "post_content_filtered^1",
    "attachments.attachment.content^1",
  ];

  $query = $data["s"];

  mx_error_log("Searching for '$query'");

  $query = [
    "bool" => [
      "must" => [
        [
          // `combined_fields` allows us to search multiple fields with the same query
          "combined_fields" => [
            "query" => $query,
            "fields" => $fields,
            "boost" => 4,
            "minimum_should_match" => "100%", // Same as `"operator" => "and"`
          ],
        ],
        [
          "multi_match" => [
            "query" => $query,
            "fields" => $fields,
            "boost" => 2,
            "fuzziness" => 0,
          ],
        ],
        [
          "multi_match" => [
            "query" => $query,
            "fields" => $fields,
            "fuzziness" => "AUTO",
          ],
        ],
      ],
      "should" => [
        [
          "multi_match" => [
            "query" => $query,
            "type" => "phrase",
            "fields" => $fields,
            "boost" => 4,
          ],
        ],
      ],
    ],
  ];

  /**
   * Filter the basic Elasticsearch bool query before performing wrapping it in a function_score query.
   * @param array $query The bool query.
   * @param array $data The Ajax request data.
   */
  $query = apply_filters("mx_search_es_query", $query, $data);

  $boosted_post_types = [];
  foreach ($post_types as $post_type) {
    if (!isset($mx_search_settings_post_types[$post_type])) {
      continue;
    }
    $boosted_post_types[$post_type] =
      $mx_search_settings_post_types[$post_type]["boost"] ?? 1;
  }
  /**
   * Filter the boosted post types before performing the search.
   * @param array $boosted_post_types The boosted post types with or without weights.
   * @param array $data The Ajax request data.
   */
  $boosted_post_types = apply_filters(
    "mx_search_boosted_post_types",
    $boosted_post_types,
    $data,
  );
  $boosted_post_type_functions = array_map(
    function ($key, $value) use ($default_boosted_post_type_weight) {
      if (is_numeric($key)) {
        $key = $value;
        $value = $default_boosted_post_type_weight;
      }
      if ($value == 1) {
        return false;
      }
      return [
        "filter" => [
          "match" => [
            "post_type" => $key,
          ],
        ],
        "weight" => $value,
      ];
    },
    array_keys($boosted_post_types),
    $boosted_post_types,
  );

  /**
   * Filter the functions corresponding to the post type boosts before passing it to the function_score query.
   * @param array $boosted_post_type_functions The boosted post type functions.
   * @param array $data The Ajax request data.
   */
  $boosted_post_type_functions = apply_filters(
    "mx_search_boosted_post_type_functions",
    $boosted_post_type_functions,
    $data,
  );

  $decaying_post_types = [];
  foreach ($post_types as $post_type) {
    if (!isset($mx_search_settings_post_types[$post_type])) {
      continue;
    }
    if (!$mx_search_settings_post_types[$post_type]["decay"] ?? false) {
      continue;
    }
    $decaying_post_types[] = $post_type;
  }
  /**
   * Filter the decaying post types before performing the search.
   * @param array $decaying_post_types The decaying post types with or without params.
   * @param array $data The Ajax request data.
   */
  $decaying_post_types = apply_filters(
    "mx_search_decaying_post_types",
    $decaying_post_types,
    $data,
  );
  $decaying_post_type_functions = array_map(
    function ($key, $value) {
      if (is_numeric($key)) {
        $key = $value;
        $value = [];
      }
      if (!is_array($value)) {
        throw new Exception("Decaying post type params must be an array.");
      }
      $value = array_merge(
        [
          "origin" => "now",
          "scale" => "30d",
          "decay" => 0.5,
          "field" => "post_date",
        ],
        $value,
      );
      $field = $value["field"];
      unset($value["field"]);

      return [
        "filter" => [
          "match" => [
            "post_type" => $key,
          ],
        ],
        "gauss" => [
          $field => $value,
        ],
      ];
    },
    array_keys($decaying_post_types),
    $decaying_post_types,
  );

  /**
   * Filter the functions corresponding to the post type decays before passing it to the function_score query.
   * @param array $decaying_post_type_functions The decaying post type functions.
   * @param array $data The Ajax request data.
   */
  $decaying_post_type_functions = apply_filters(
    "mx_search_decaying_post_type_functions",
    $decaying_post_type_functions,
    $data,
  );

  $function_score = [
    "query" => $query,
    "score_mode" => "multiply", // How the scores of the functions are combined.
    "boost_mode" => "multiply", // How the result of the functions is combined with the base score of the document.
    "functions" => [
      ...array_filter(array_values($boosted_post_type_functions)),
      ...array_filter(array_values($decaying_post_type_functions)),
    ],
  ];

  /**
   * Filter the function query before performing the search.
   * @param array $function_score The Elasticsearch function_score query that wraps the base query.
   * @param array $data The Ajax request data.
   * @return array The modified function_score query.
   */
  $function_score = apply_filters(
    "mx_search_es_function_score",
    $function_score,
    $data,
  );

  $es_body = [
    "query" => [
      "function_score" => $function_score,
    ],
    "highlight" => [
      "pre_tags" => ["<mark>"],
      "post_tags" => ["</mark>"],
      "fields" => [
        "post_title" => [
          "number_of_fragments" => 0,
        ],
        "attachments.attachment.title" => [
          "number_of_fragments" => 0,
        ],
        "post_content_filtered" => [
          "number_of_fragments" => 3,
          "fragment_size" => 150,
        ],
        "attachments.attachment.content" => [
          "number_of_fragments" => 3,
          "fragment_size" => 150,
        ],
      ],
    ],
    "from" => (($data["page"] ?: 1) - 1) * $data["hitsPerPage"],
    "size" => $data["hitsPerPage"],
  ];

  /**
   * Filter the Elasticsearch body before performing the search.
   * @param array $es_body The Elasticsearch query body.
   * @param array $data The Ajax request data.
   */
  $es_body = apply_filters("mx_search_es_body", $es_body, $data);

  try {
    $es_results = mx_search_perform_es_search($es_body);

    $total = $es_results["hits"]["total"]["value"] ?? null;

    $hit_source_mapping = [
      "title" => fn($hit) => mx_coalesce_string([
        $hit["highlight"]["attachments.attachment.title"] ?? "",
        $hit["_source"]["attachments"][0]["attachment"]["title"] ?? "",
        $hit["highlight"]["post_title"] ?? "",
        $hit["_source"]["post_title"] ?? "",
      ]),

      "excerpt" => fn($hit) => mx_coalesce_string([
        $hit["highlight"]["attachments.attachment.content"] ?? "",
        wp_trim_words(
          $hit["_source"]["attachments"][0]["attachment"]["content"] ?? "",
          40,
        ),
        $hit["highlight"]["post_content_filtered"] ?? "",
        wp_trim_words($hit["_source"]["post_content_filtered"] ?? "", 40),
      ]),

      "href" => fn($hit) => ($hit["_source"]["post_type"] ?? null) ==
      "attachment"
        ? $hit["_source"]["guid"]
        : $hit["_source"]["permalink"],

      "image" => fn($hit) => get_the_post_thumbnail_url(
        $hit["_source"]["post_id"],
      ),

      "date" => fn($hit) => in_array(
        get_post_type($hit["_source"]["post_id"]),
        ["post"],
      )
        ? $hit["_source"]["post_date"]
        : null,

      "type" => fn($hit) => get_post_type_labels(
        get_post_type_object(get_post_type($hit["_source"]["post_id"])),
      )->singular_name ?? null,

      "score" => fn($hit) => $hit["_score"] ?? null,
    ];

    /**
     * Filter the hit source mapping before transforming the hits.
     * @param array $hit_source_mapping The hit source mapping.
     * @param array $es_body The Elasticsearch query body.
     * @param array $data The Ajax request data.
     */
    $hit_source_mapping = apply_filters(
      "mx_search_hit_source_mapping",
      $hit_source_mapping,
      $es_body,
      $data,
    );

    $results = [
      "success" => true,
      "hits" => array_map(function ($hit) use (
        $es_results,
        $hit_source_mapping,
        $es_body,
        $data,
      ) {
        $transformed_hit = array_map(function ($fn) use ($hit) {
          return $fn($hit);
        }, $hit_source_mapping);

        /**
         * Filter the transformed hit before returning it.
         * @param array $transformed_hit The transformed hit.
         * @param array $hit The original hit.
         * @param array $es_results The Elasticsearch results.
         * @param array $es_body The Elasticsearch query body.
         * @param array $data The Ajax request data.
         */
        return apply_filters(
          "mx_search_es_hit",
          $transformed_hit,
          $hit,
          $es_results,
          $es_body,
          $data,
        );
      }, $es_results["hits"]["hits"] ?? []),
      "total" => $total,
      "totalPages" => ceil($total / $data["hitsPerPage"]),
    ];

    /**
     * Filter the search results before returning them.
     * @param array $results The search results.
     * @param array $es_results The Elasticsearch results.
     */
    $results = apply_filters("mx_search_results", $results, $es_results);

    wp_send_json($results);
  } catch (Exception $e) {
    mx_error_log($e->getMessage());
    return wp_send_json([
      "success" => false,
      "error" => "An error occurred while searching.",
    ]);
  } finally {
    wp_die();
  }
}
add_action("wp_ajax_mx_search", "mx_search_ajax_handler");
add_action("wp_ajax_nopriv_mx_search", "mx_search_ajax_handler"); // For non-logged-in users

/**
 * Strips all HTML tags from the post_content_filtered field before indexing.
 */
add_filter(
  "ep_post_sync_args_post_prepare_meta",
  function (array $post_args, string|int $post_id): array {
    $post_args["post_content_filtered"] = mx_plain_text(
      $post_args["post_content_filtered"],
      ["exclude" => ".modularity-edit-module"],
    );
    return $post_args;
  },
  20,
  2,
);

/**
 * Adds stemming
 */
add_filter("ep_post_mapping", function ($mapping) {
  $mapping["settings"]["analysis"]["analyzer"]["default"]["filter"][] =
    "swedish_stemmer";
  $mapping["settings"]["analysis"]["filter"]["swedish_stemmer"] = [
    "type" => "stemmer",
    "name" => "swedish",
  ];
  return $mapping;
});

/**
 * Adds a "Search" ACF field group to all indexable post types.
 */
add_action("acf/init", function () {
  if (!class_exists("\ElasticPress\Indexables")) {
    return;
  }
  /**
   * @var \ElasticPress\Indexable\Post $indexable
   */
  $indexable = \ElasticPress\Indexables::factory()->get("post");
  $post_types = $indexable->get_indexable_post_types();

  acf_add_local_field_group([
    "key" => "group_search",
    "title" => __("Search", "municipio-extended"),
    "fields" => [
      // [
      //   "key" => "field_search_excluded",
      //   "label" => __("Exclude from search", "municipio-extended"),
      //   "name" => "search_excluded",
      //   "type" => "true_false",
      //   "instructions" => __(
      //     "Check this box to exclude this post from search results.",
      //     "municipio-extended",
      //   ),
      //   "ui" => 1,
      //   "default_value" => 0,
      // ],
      [
        "key" => "field_search_keywords",
        "label" => __("Keywords", "municipio-extended"),
        "name" => "search_keywords",
        "type" => "textarea",
        "instructions" => __(
          "Seperate keywords with commas or new lines.",
          "municipio-extended",
        ),
        "rows" => 3,
        "new_lines" => "lf",
      ],
    ],
    "position" => "side",
    "location" => array_map(function ($post_type) {
      return [
        [
          "param" => "post_type",
          "operator" => "==",
          "value" => $post_type,
        ],
      ];
    }, $post_types),
  ]);
});

/**
 * Takes the standard EP checkbox for excluding from search into account.
 */
add_filter("mx_search_es_query", function ($query) {
  $query["bool"]["must_not"][] = [
    "terms" => [
      "meta.ep_exclude_from_search.raw" => ["1"],
    ],
  ];
  return $query;
});

/**
 * Filter documents by mime type
 */
add_filter("mx_search_es_query", function ($query) {
  /**
   * @var \ElasticPress\Feature\Documents\Documents $feature
   */
  $feature = \ElasticPress\Features::factory()->get_registered_feature(
    "documents",
  );
  $mime_types = $feature->get_allowed_ingest_mime_types();
  $mime_types[] = ""; // This let's us query non-attachments as well as attachments.

  $mime_types = array_unique(array_values($mime_types));

  $query["bool"]["must"][] = [
    "terms" => [
      "post_mime_type" => $mime_types,
    ],
  ];
  return $query;
});

/**
 * Removes unsupported features from the ElasticPress admin menu.
 */
add_action(
  "admin_menu",
  function () {
    $menu_slug =
      defined("EP_IS_NETWORK") &&
      EP_IS_NETWORK &&
      !Utils\is_top_level_admin_context()
        ? "elasticpress"
        : "elasticpress-weighting";
    remove_submenu_page("elasticpress", $menu_slug);

    remove_submenu_page("elasticpress", "edit.php?post_type=ep-pointer");

    remove_submenu_page("elasticpress", "elasticpress-synonyms");
  },
  60,
);

/**
 * Hides the unsupported ep-pointer post type from wp-admin.
 */
add_action(
  "init",
  function () {
    $post_type = "ep-pointer";
    $post_type_object = get_post_type_object($post_type);
    if ($post_type_object) {
      $post_type_object->show_ui = false;
      $post_type_object->show_in_menu = false;
    }
  },
  20,
);

/**
 * Adds a field group to the acf-options-search page
 */
add_action(
  "init",
  function () {
    if (!function_exists("acf_add_local_field_group")) {
      return;
    }
    $post_types = mx_get_regular_post_types("objects");
    $post_types["attachment"] = get_post_type_object("attachment");

    acf_add_local_field_group([
      "key" => "group_mx_search_settings",
      "title" => __("Search settings", "municipio-extended"),
      "fields" => [
        [
          "key" => "field_mx_search_settings_post_types",
          "label" => __("Post type settings", "municipio-extended"),
          "name" => "mx_search_settings_post_types",
          "type" => "group",
          "layout" => "horizontal",
          "sub_fields" => array_map(function ($post_type) {
            return [
              "key" => "field_mx_search_settings_post_types_{$post_type->name}",
              "label" => $post_type->label,
              "name" => $post_type->name,
              "type" => "group",
              "layout" => "horizontal",
              "sub_fields" => [
                [
                  "key" => "field_mx_search_settings_post_types_{$post_type->name}_boost",
                  "label" => __("Boost", "municipio-extended"),
                  "name" => "boost",
                  "type" => "number",
                  "instructions" => __(
                    "The boost factor for this post type.",
                    "municipio-extended",
                  ),
                  "default_value" => 1,
                  "wrapper" => [
                    "width" => "50%",
                  ],
                ],
                [
                  "key" => "field_mx_search_settings_post_types_{$post_type->name}_decay",
                  "label" => __("Decay", "municipio-extended"),
                  "name" => "decay",
                  "type" => "true_false",
                  "ui" => 1,
                  "instructions" => __(
                    "Whether older posts should have lower scores.",
                    "municipio-extended",
                  ),
                  "default_value" => 0,
                  "wrapper" => [
                    "width" => "50%",
                  ],
                ],
              ],
            ];
          }, $post_types),
        ],
      ],
      "location" => [
        [
          [
            "param" => "options_page",
            "operator" => "==",
            "value" => "acf-options-search",
          ],
        ],
      ],
    ]);
  },
  20,
);
