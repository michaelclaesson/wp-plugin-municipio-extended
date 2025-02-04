<?php

/**
 * Adds new Taxonomifilter field to Posts modules
 */
add_action(
  "init",
  function () {
    if (!function_exists("acf_add_local_field")) {
      return;
    }
    $post_types = get_post_types(
      [
        // 'public' => true,
        // 'show_ui' => true,
      ],
      "objects",
    );
    $taxonomies = get_taxonomies(
      [
        "public" => true,
        "show_ui" => true,
      ],
      "objects",
    );
    $tax_choices = array_combine(
      array_keys($taxonomies),
      array_map(function ($taxonomy) use ($post_types) {
        $label = $taxonomy->label;
        $object_type = $taxonomy->object_type;
        if (!empty($object_type)) {
          $label .= " (";
          $count = 0;
          foreach ($object_type as $post_type) {
            if ($count > 0) {
              $label .= ", ";
            }
            $label .= $post_types[$post_type]->label;
            $count++;
          }
          $label .= ")";
        }
        return $label;
      }, $taxonomies),
    );

    $sub_fields = [
      [
        "key" => "field_mod_posts_filtering_taxonomy",
        "label" => "Taxonomi",
        "name" => "taxonomy",
        "type" => "select",
        "instructions" => "",
        "required" => 1,
        "conditional_logic" => 0,
        "wrapper" => [
          "width" => "",
          "class" => "",
          "id" => "",
        ],
        "choices" => $tax_choices,
        "default_value" => false,
        "allow_null" => 0,
        "multiple" => 0,
        "ui" => 0,
        "return_format" => "value",
        "ajax" => 0,
        "placeholder" => "",
      ],
      [
        "key" => "field_mod_posts_filtering_operator",
        "label" => "Operator",
        "name" => "operator",
        "type" => "select",
        "instructions" => "",
        "required" => 0,
        "conditional_logic" => 0,
        "wrapper" => [
          "width" => "",
          "class" => "",
          "id" => "",
        ],
        "choices" => [
          "IN" => "Är lika med",
          "NOT IN" => "Är inte lika med",
        ],
        "default_value" => "=",
        "allow_null" => 0,
        "multiple" => 0,
        "ui" => 0,
        "return_format" => "value",
        "ajax" => 0,
        "placeholder" => "",
      ],
    ];
    foreach (array_keys($tax_choices) as $taxonomy) {
      $sub_fields[] = [
        "key" => "field_mod_posts_filtering_term_{$taxonomy}",
        "label" => "Term",
        "name" => "term_{$taxonomy}",
        "type" => "taxonomy",
        "instructions" => "",
        "required" => 1,
        "conditional_logic" => [
          [
            [
              "field" => "field_mod_posts_filtering_taxonomy",
              "operator" => "==",
              "value" => $taxonomy,
            ],
          ],
        ],
        "wrapper" => [
          "width" => "",
          "class" => "",
          "id" => "",
        ],
        "taxonomy" => $taxonomy,
        "field_type" => "select",
        "allow_null" => 0,
        "add_term" => 0,
        "save_terms" => 0,
        "load_terms" => 0,
        "return_format" => "id",
        "multiple" => 0,
      ];
    }
    $field = [
      "parent" => "group_571e045dd555d", // Data filtering on Posts module
      "key" => "field_mod_posts_filtering",
      "label" => "Taxonomifilter",
      "name" => "mod_posts_filtering",
      "type" => "repeater",
      "instructions" =>
        "Endast inlägg som uppfyller alla dessa villkor kommer visas i modulen.",
      "required" => 0,
      "conditional_logic" => [
        0 => [
          0 => [
            "field" => "field_571e046536f0f", // Taxonomy filtering
            "operator" => "==",
            "value" => "1",
          ],
        ],
      ],
      "wrapper" => [
        "width" => "",
        "class" => "",
        "id" => "",
      ],
      "collapsed" => "",
      "min" => 1,
      "max" => 0,
      "layout" => "block",
      "button_label" => "Lägg till filter",
      "sub_fields" => $sub_fields,
    ];
    acf_add_local_field($field);
  },
  11, // Run after dynamic taxonomies are added in municipio/library/AcfFields/php/options-theme-custom-taxonomy.php
);

/**
 * Removes standard taxonomy filter fields
 */
add_action("init", function () {
  if (!function_exists("acf_remove_local_field")) {
    return;
  }
  acf_remove_local_field("field_571e048136f10");
  acf_remove_local_field("field_609a6c2fae66e");
  acf_remove_local_field("field_571e049636f11");
});

/**
 * Moves the new field to the correct position
 */
add_action("acf/input/admin_enqueue_scripts", function () {
  $css = "
    #acf-group_571e045dd555d > .acf-fields {
      display: flex;
      flex-wrap: wrap;
      align-items: stretch;
    }
    #acf-group_571e045dd555d > .acf-fields > .acf-field {
      width: 100%;
    }
    .acf-field-571e046536f0f {
      order: -2;
    }
    .acf-field-mod-posts-filtering {
      order: -1;
    }
  ";
  wp_register_style("acf-mod-posts-filtering", false);
  wp_enqueue_style("acf-mod-posts-filtering");
  wp_add_inline_style("acf-mod-posts-filtering", $css);
});

// /**
//  * Replaces the filtering logic
//  */
add_filter(
  "modularity/mod_posts_taxonomy_filters",
  function ($taxonomy_filters, $fields, $module) {
    $filters = $fields->mod_posts_filtering;
    if (!empty($filters)) {
      $taxonomy_filters = [];
      foreach ($filters as $filter) {
        $taxonomy_filters["filter[" . $filter->taxonomy . "]"] =
          $filter->{"term_{$filter->taxonomy}"};
      }
    }
    return $taxonomy_filters;
  },
  10,
  3,
);
add_filter(
  "modularity/mod_posts_tax_query",
  function ($tax_query, $fields, $module_id) {
    $filters = $fields->mod_posts_filtering;
    if (!empty($filters)) {
      $tax_query = [];
      foreach ($filters as $filter) {
        $tax_query[] = [
          "taxonomy" => $filter->taxonomy,
          "field" => "id",
          "terms" => $filter->{"term_{$filter->taxonomy}"},
          "operator" => $filter->operator,
        ];
      }
    }
    return $tax_query;
  },
  10,
  3,
);
