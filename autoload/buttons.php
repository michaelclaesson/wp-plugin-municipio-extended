<?php

function mx_prosify($content, $options = []) {
  $content = preg_replace_callback(
    '/(<a\s[^>]*class="((?:[^"]+\s)c-button)([^"]*)"\s[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>)/',
    function ($matches) {
      $classes = preg_split("/\s+/", $matches[2]);
      $href = $matches[4];
      $label = strip_tags($matches[5]);
      return mx_render_view("mxui.button", [
        "href" => $href,
        "label" => $label,
        "variant" => in_array("c-button__outlined", $classes)
          ? "outlined"
          : (in_array("c-button__filled--primary", $classes)
            ? "primary"
            : (in_array("c-button__filled--secondary", $classes)
              ? "secondary"
              : "default")),
      ]);
    },
    $content,
  );
  return $content;
}
