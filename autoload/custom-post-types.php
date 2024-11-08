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
