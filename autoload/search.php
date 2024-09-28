<?php

use Elastic\Elasticsearch\ClientBuilder;

function mx_search_perform_es_search($body) {
  $hosts = [defined("EP_HOST") ? constant("EP_HOST") : get_option("ep_host")];
  $index = \ElasticPress\Indexables::factory()
    ->get("post")
    ->get_index_name(null);

  $client = ClientBuilder::create()->setHosts($hosts)->build();

  return $client->search([
    "index" => $index,
    "body" => $body,
  ]);
}

function mx_search_ajax_handler() {
  check_ajax_referer("mx_search", "nonce");

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
          "multi_match" => [
            "query" => $query,
            "fields" => $fields,
            "boost" => 4,
            "minimum_should_match" => "100%",
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

  $query = apply_filters("mx_search_es_query", $query, $data);

  $es_body = [
    "query" => [
      "function_score" => [
        "query" => $query,
        "score_mode" => "avg",
        "boost_mode" => "sum",
      ],
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

      "href" => fn($hit) => $hit["_source"]["permalink"],

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
