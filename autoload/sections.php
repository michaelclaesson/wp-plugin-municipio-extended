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
