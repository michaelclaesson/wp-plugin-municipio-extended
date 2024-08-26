<?php

/**
 * Fixes problem with woff2 fonts not being allowed to be uploaded and getting
 * the wrong MIME type.
 */
add_filter("upload_mimes", function ($mimes) {
  $mimes["woff2"] = "font/woff2";
  return $mimes;
});

/**
 * Utility function to get all fonts uploaded to the media library.
 */
function mx_get_uploaded_fonts() {
  $fonts = get_posts([
    "post_type" => "attachment",
    "post_mime_type" => "font/woff2",
    "posts_per_page" => -1,
  ]);
  return $fonts;
}

/**
 * Adds @font-face styles for all uploaded fonts to the head of the document.
 */
add_action("wp_head", function () {
  $fonts = mx_get_uploaded_fonts();
  $font_css = "";
  foreach ($fonts as $font) {
    $url = wp_get_attachment_url($font->ID);
    $font_css .= "@font-face{font-family:'{$font->post_title}';src: url('{$url}') format('woff2');}";
  }
  echo "<style>{$font_css}</style>";
});

/**
 * Adds all uploaded fonts to the list of standard fonts in the Kirki plugin.
 */
add_filter("kirki/fonts/standard_fonts", function ($standard_fonts) {
  $fonts = mx_get_uploaded_fonts();
  foreach ($fonts as $font) {
    $standard_fonts[$font->post_title] = [
      "label" => $font->post_title,
      "variants" => [
        "100",
        "100italic",
        "200",
        "200italic",
        "300",
        "300italic",
        "400",
        "400italic",
        "500",
        "500italic",
        "600",
        "600italic",
        "700",
        "700italic",
        "800",
        "800italic",
        "900",
        "900italic",
      ],
      "stack" => $font->post_title . ", sans-serif",
    ];
  }
  return $standard_fonts;
});
