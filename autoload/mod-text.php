<?php

add_filter("Modularity/Display/mod-text/viewData", function ($data) {
  if (!empty($data["post_content"])) {
    $data["post_content"] = wpautop($data["post_content"]);
    $data["post_content"] = do_shortcode($data["post_content"]);
    $data["post_content"] = mx_replace_builtin_classes($data["post_content"]);
  }
  return $data;
});

function mx_replace_builtin_classes($content) {
  return str_replace(
    [
      //Old inline transition button
      "btn-theme-first",
      "btn-theme-second",
      "btn-theme-third",
      "btn-theme-fourth",
      "btn-theme-fifth",

      //Gutenberg block image
      "wp-block-image",
      "wp-element-caption",
      "<figcaption>",
    ],
    [
      //Old inline transition button
      "c-button c-button__filled c-button__filled--primary c-button--md",
      "c-button c-button__filled c-button__filled--secondary c-button--md",
      "c-button c-button__filled c-button__filled--secondary c-button--md",
      "c-button c-button__filled c-button__filled--secondary c-button--md",
      "c-button c-button__filled c-button__filled--secondary c-button--md",

      //Gutenberg block image
      "c-image",
      "c-image__caption",
      '<figcaption class="c-image__caption">',
    ],
    $content,
  );
}

function mx_process_content($content, $options = []) {
  if (!($options["wpautop"] ?? false)) {
    remove_filter("the_content", "wpautop");
  }
  $content = mx_replace_builtin_classes($content);
  $content = trim($content);
  $content = apply_filters("the_content", $content);
  $content = do_shortcode($content);
  if (!($options["wpautop"] ?? false)) {
    add_filter("the_content", "wpautop");
  }
  return $content;
}
