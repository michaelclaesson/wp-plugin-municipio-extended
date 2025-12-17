<?php

add_filter("acf/load_field/key=field_57454c7ad44dc", function ($field) {
  $field["label"] = __("Video URL or embed code", "municipio-extended");
  $field["instructions"] = __(
    "Only supports YouTube URLs and MediaFlow embed codes",
    "municipio-extended",
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

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug) {
    if ($slug === "video") {
      $template = "mod-video.blade.php";
    }
    return $template;
  },
  10,
  2,
);

/**
 * Replaces Modularity's default Video module controller with our version.
 */
add_filter(
  "Modularity/Modules",
  function ($modules) {
    $file = array_search("Video", $modules, true);
    unset($modules[$file]);
    $class = new \ReflectionClass("MunicipioExtended\Modularity\Video\Video");
    $file = dirname($class->getFileName());
    $modules[$file] = "Video";
    return $modules;
  },
  10,
  1,
);
