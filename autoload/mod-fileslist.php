<?php

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug) {
    if ($slug === "fileslist") {
      $template = "mod-fileslist.blade.php";
    }
    return $template;
  },
  10,
  2,
);
