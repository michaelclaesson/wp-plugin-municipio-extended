<?php

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug) {
    if ($slug === "contacts") {
      $template = "mod-contacts.blade.php";
    }
    return $template;
  },
  10,
  2,
);
