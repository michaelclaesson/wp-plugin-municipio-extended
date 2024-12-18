<?php

/**
 * Adds mixed option to display modes
 */
add_filter(
  "acf/load_field/key=field_571dfd4c0d9d9",
  function ($field) {
    $field["choices"]["mixed"] = _x(
      "Cards and list",
      "Posts Module Display Mode",
      "municipio-extended",
    );

    $options_to_remove = ["items", "news", "grid", "features-grid"];

    foreach ($options_to_remove as $option) {
      if (isset($field["choices"][$option])) {
        unset($field["choices"][$option]);
      }
    }
    return $field;
  },
  99,
);

add_filter(
  "/Modularity/externalViewPath",
  function ($paths) {
    $paths["mod-posts"][] = mx_get_default_module_view_path("mod-posts");
    $paths["mod-posts"][] = MUNICIPIO_EXTENDED_PATH . "/views/mod-posts";
    return $paths;
  },
  2,
);

add_filter("Modularity/Module/Posts/TemplateController/Mixed", function () {
  return "MunicipioExtended\\Modularity\\ModPosts\\TemplateController\\MixedTemplate";
});

add_filter("Modularity/Display/mod-posts/viewData", function ($data) {
  $data["lang"]["readMore"] = "";
  return $data;
});

// Add taxonomies field to cards and list
add_filter("acf/load_field/key=field_571e01e7f246c", function ($field) {
  $field["conditional_logic"] = [
    [
      [
        "field" => "field_571dfd4c0d9d9",
        "operator" => "==",
        "value" => "mixed",
      ],
    ],
  ];

  $field["choices"]["taxonomies"] = __("Visa taxonomier", "municipio-extended");

  return $field;
});

// Add sub-field for taxonomy selection under "Fält"
add_action("acf/init", function () {
  acf_add_local_field([
    "key" => "field_taxonomy_selection_in_fields",
    "label" => __("Taxonomier att visa för kort", "municipio-extended"),
    "name" => "taxonomy_selection_in_fields",
    "type" => "checkbox",
    "parent" => "group_571dfd3c07a77",
    "conditional_logic" => [
      [
        [
          "field" => "field_571e01e7f246c",
          "operator" => "==",
          "value" => "taxonomies",
        ],
      ],
    ],
    "choices" => [],
    "layout" => "horizontal",
  ]);
});

// Populate taxonomy choices
add_filter("acf/load_field/key=field_taxonomy_selection_in_fields", function (
  $field,
) {
  $taxonomies = get_taxonomies(["public" => true], "objects");

  foreach ($taxonomies as $taxonomy) {
    $field["choices"][$taxonomy->name] = $taxonomy->label;
  }

  return $field;
});
