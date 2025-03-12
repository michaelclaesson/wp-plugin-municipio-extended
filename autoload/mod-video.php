<?php

add_filter("acf/load_field/key=field_57454c7ad44dc", function ($field) {
  $field["label"] = __("Video URL or embed code", "mu-plugins");
  $field["instructions"] = __(
    "Only supports YouTube URLs and MediaFlow embed codes",
    "mu-plugins",
  );
  $field["type"] = "textarea";
  return $field;
});

add_filter("Modularity/Display/mod-video/viewData", function ($data) {
  if (!empty($data)) {
    $embedLink = $data["embed_link"];
    if (
      is_string($embedLink) &&
      preg_match("/\bplay\.mediaflow(pro)?\b/", $embedLink)
    ) {
      preg_match(
        '/"([^"]+\bplay\.mediaflow(?:pro)?\b[^"]+)"/',
        $embedLink,
        $matches,
      );
      if (isset($matches[1])) {
        $data["embed_link"] = $matches[1];
      }
    }
  }
  return $data;
});

add_filter(
  "Modularity/Display/mod-video/pre_getEmbedMarkup",
  function ($markup, $embedLink) {
    if (
      is_string($embedLink) &&
      preg_match("/\\bplay\\.mediaflow(pro)?\\b/", $embedLink)
    ) {
      // Remove 'position:relative;' styles from the embedLink
      $embedLink = preg_replace(
        '/style="[^"]*position:\s*relative;?[^"]*"/',
        "",
        $embedLink,
      );
      return $embedLink;
    }
    return $markup;
  },
  10,
  2,
);
