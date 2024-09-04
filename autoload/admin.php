<?php

/**
 * Disables the metadata tinymce plugin.
 */
add_filter("Municipio/Admin/EnableMetaDataPlugin", "__return_false");

/**
 * Disables advanced html tags (div, style, script, and others) in the tinymce
 * editor. They conflict with the "more" tag
 */
add_filter("Municipio/Admin/AllowAdvancedHtmlTags", "__return_false");

/**
 * Removes `pre` from the available block formats.
 */
add_filter(
  "tiny_mce_before_init",
  function ($settings) {
    $block_formats = explode(";", $settings["block_formats"]);
    $block_formats = array_diff($block_formats, ["Preformatted=pre"]);
    $settings["block_formats"] = implode(";", $block_formats);
    return $settings;
  },
  11,
);

add_filter(
  "default_hidden_meta_boxes",
  function ($hidden, \WP_Screen $screen) {
    $hidden[] = "acf-group_64227d79a7f57"; // Snabblänkar
    $hidden[] = "acf-group_search"; // Sök
    $hidden[] = "acf-group_646c5d26e3359"; // Uteslut sidans titel från Google Translate
    $hidden[] = "acf-group_56c33cf1470dc"; // Visningsinställningar
    $hidden[] = "tsf-inpost-box"; // SEO Settings
    $hidden[] = "acf-group_636e424039120"; // Språk för skärmläsare
    $hidden[] = "slugdiv"; // Slug
    $hidden[] = "authordiv"; // Författare
    $hidden[] = "acfe-author"; // Författare
    if ($screen->post_type !== "page") {
      $hidden[] = "pageparentdiv"; // Sidattribut/Inläggsattribut
    }
    return $hidden;
  },
  10,
  2,
);

/**
 * Places ACF sidebar field groups below the core metaboxes.
 */
add_filter(
  "acf/input/meta_box_priority",
  function ($priority, $field_group) {
    $context = $field_group["position"]; // normal, side, acf_after_title
    // Reduce priority for sidebar metaboxes for best position.
    if ($context == "side") {
      $priority = "low";
    }
    return $priority;
  },
  10,
  2,
);
