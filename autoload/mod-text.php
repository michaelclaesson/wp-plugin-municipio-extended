<?php

add_filter("Modularity/Display/mod-text/viewData", function ($data) {
  if (!empty($data["post_content"])) {
    $data["post_content"] = mx_replace_builtin_classes($data["post_content"]);
  }
  return $data;
});

function mx_replace_builtin_classes($content) {
  return str_replace(
    [
      "wp-caption",
      "c-image-text",
      "wp-image-",
      "alignleft",
      "alignright",
      "alignnone",
      "aligncenter",

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
      "c-image",
      "c-image__caption",
      "c-image__image wp-image-",
      'u-float--left@sm u-float--left@md u-float--left@lg u-float--left@xl u-float--left@xl u-margin__y--2 
              u-margin__right--2@sm u-margin__right--2@md u-margin__right--2@lg u-margin__right--2@xl 
              u-width--100@xs',
      'u-float--right@sm u-float--right@md u-float--right@lg u-float--right@xl u-float--right@xl 
              u-margin__y--2 u-margin__left--2@sm u-margin__left--2@md u-margin__left--2@lg u-margin__left--2@xl 
              u-width--100@xs',
      "",
      "u-margin__x--auto u-text-align--center",

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
