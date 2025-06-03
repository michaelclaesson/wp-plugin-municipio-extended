<?php

add_action(
  "admin_init",
  function () {
    if (function_exists("acf_remove_local_field")) {
      // Section card
      acf_remove_local_field("field_63ff1f5c24e10"); //Height
      acf_remove_local_field("field_63ff205324e11"); //Spacing Top
      acf_remove_local_field("field_63ff207624e12"); //Spacing Bottom
      acf_remove_local_field("field_63ff209124e13"); //Full width stretch
      // Full width section
      acf_remove_local_field("field_61543393334c3"); //Height
      acf_remove_local_field("field_61543393334c7"); //Spacing Top
      acf_remove_local_field("field_61543393334cc"); //Spacing Bottom
      // Split-featured sections
      acf_remove_local_field("field_60d1a9935551d"); //Height
      acf_remove_local_field("field_60d2f7b110b0b"); //Spacing Top
      acf_remove_local_field("field_60d2f7cc10b0c"); //Spacing Bottom
    }
  },
  20,
);

add_filter("Modularity/Display/mod-section-full/viewData", function ($data) {
  if (!empty($data["meta"]["text"][0])) {
    $data["text"] = mx_process_content($data["meta"]["text"][0]);
  }
  return $data;
});

/**
 * Enables full wysiwyg editor for all Modularity Sections modules
 */
add_filter("acf/load_field/key=field_63ff1e7124e0e", function ($field) {
  $field["type"] = "wysiwyg";
  $field["toolbar"] = "full";
  return $field;
});
add_filter("acf/load_field/key=field_6154339333497", function ($field) {
  $field["toolbar"] = "full";
  return $field;
});
add_filter("acf/load_field/key=field_60d1a8040b829", function ($field) {
  $field["type"] = "wysiwyg";
  $field["toolbar"] = "full";
  return $field;
});

/**
 * Adds a checkbox to the section modules that allows editors to remove the spacing below them.
 */
add_action(
  "acf/init",
  function () {
    acf_add_local_field_group([
      "key" => "group_module_layout",
      "title" => _x("Layout", "Module Field Group Label", "municipio-extended"),
      "fields" => [
        [
          "key" => "field_module_layout_remove_spacing_below",
          "label" => __("Remove spacing below", "municipio-extended"),
          "name" => "module_layout_remove_spacing_below",
          "type" => "true_false",
          "instructions" => __(
            "Check this to remove the spacing below this section.",
            "municipio-extended",
          ),
          "ui" => 1,
        ],
      ],
      "location" => [
        [
          [
            "param" => "post_type",
            "operator" => "==",
            "value" => "mod-section-featured",
          ],
        ],
        [
          [
            "param" => "post_type",
            "operator" => "==",
            "value" => "mod-section-full",
          ],
        ],
        [
          [
            "param" => "post_type",
            "operator" => "==",
            "value" => "mod-section-split",
          ],
        ],
      ],
    ]);
  },
  20,
);

add_filter(
  "Modularity/Display/BeforeModule::classes",
  function ($classes, $args, $module_type, $module_id) {
    if (
      in_array($module_type, [
        "mod-section-featured",
        "mod-section-full",
        "mod-section-split",
      ])
    ) {
      $remove_spacing_below = get_field(
        "module_layout_remove_spacing_below",
        $module_id,
      );
      if ($remove_spacing_below) {
        $classes[] = "-u-margin-after--grid-gap";
      }
    }
    return $classes;
  },
  10,
  4,
);
