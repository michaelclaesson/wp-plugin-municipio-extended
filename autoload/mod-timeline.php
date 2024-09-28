<?php

add_filter(
  "Modularity/Display/BeforeModule::classes",
  function ($classes, $args, $post_type) {
    if ($post_type === "mod-timeline") {
      $classes = array_diff($classes, ["modularity-mod-timeline"]);
    }
    return $classes;
  },
  10,
  3,
);

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug) {
    if ($slug === "timeline") {
      $template = "mod-timeline.blade.php";
    }
    return $template;
  },
  10,
  2,
);
