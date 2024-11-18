<?php

// Remove "Exclude from search" field in Appearance -> Post Types
add_filter(
  "acf/load_fields",
  function ($fields, $field_group) {
    if ($field_group["key"] === "group_56b34353ef1eb") {
      foreach ($fields as &$field) {
        if ($field["key"] === "field_56b347f3ffb6c") {
          $field["sub_fields"] = array_filter($field["sub_fields"], function (
            $sub_field,
          ) {
            return $sub_field["key"] !== "field_56b362255defe";
          });
        }
      }
    }
    return $fields;
  },
  10,
  2,
);

add_action("acf/init", function () {
  if (function_exists("acf_add_local_field")) {
    acf_add_local_field([
      "parent" => "field_56b347f3ffb6c",
      "key" => "field_post_type_singular_name",
      "label" => __("Post type singular name", "municipio-extended"),
      "name" => "post_type_singular_name",
      "type" => "text",
      "instructions" => __(
        "Enter the singular name for this post type.",
        "municipio-extended",
      ),
      "required" => 1,
      "conditional_logic" => 0,
      "wrapper" => [
        "width" => "",
        "class" => "",
        "id" => "",
      ],
    ]);
  }
});


add_filter(
  "Municipio/CustomPostType/labels",
  function ($labels, $typeDefinition) {
    $singularName =
      isset($typeDefinition["post_type_singular_name"]) &&
      !empty($typeDefinition["post_type_singular_name"])
        ? $typeDefinition["post_type_singular_name"]
        : $typeDefinition["post_type_name"];

    $labels["singular_name"] = $singularName;
    $labels["add_new_item"] = sprintf(
      __("Add new %s", "municipio"),
      $singularName,
    );
    $labels["new_item"] = sprintf(__("New %s", "municipio"), $singularName);
    $labels["edit_item"] = sprintf(__("Edit %s", "municipio"), $singularName);
    $labels["view_item"] = sprintf(__("View %s", "municipio"), $singularName);

    return $labels;
  },
  10,
  2,
);

add_filter("acf/load_field/key=field_56b3619c5defc", function ($field) {
  $field["label"] = __("Post type plural name", "municipio-extended");
  $field["instructions"] = __(
    "Enter the plural name for this post type. Select a name with care! Cannot be changed.",
    "municipio-extended",
  );
  return $field;
});
