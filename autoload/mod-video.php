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
      preg_match("/\bplay\.mediaflowpro\b/", $embedLink)
    ) {
      preg_match(
        '/"([^"]+\bplay\.mediaflowpro\b[^"]+)"/',
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
