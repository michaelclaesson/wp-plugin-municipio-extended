<?php

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug) {
    if ($slug === "iframe") {
      $template = "mod-iframe.blade.php";
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
    $file = array_search("Iframe", $modules, true);
    unset($modules[$file]);
    $class = new \ReflectionClass("MunicipioExtended\Modularity\Iframe\Iframe");
    $file = dirname($class->getFileName());
    $modules[$file] = "Iframe";
    return $modules;
  },
  10,
  1,
);
