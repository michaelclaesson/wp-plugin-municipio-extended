<?php

add_filter(
  "Modularity/Module/TemplatePath",
  function ($paths) {
    $new_paths[] = MUNICIPIO_EXTENDED_PATH . "/views";
    $new_paths[] = MU_PLUGINS_DIR . "/views";
    $paths = array_merge($paths, $new_paths);
    return $paths;
  },
  2,
);

add_filter("Modularity/CoreTemplatesSearchPaths", function ($paths) {
  $new_paths[] = MUNICIPIO_EXTENDED_PATH . "/views";
  $new_paths[] = MU_PLUGINS_DIR . "/views";
  $paths = array_merge($paths, $new_paths);
  return $paths;
});

add_filter("Municipio/viewPaths", function ($paths) {
  $new_paths[] = MUNICIPIO_EXTENDED_PATH . "/views";
  $new_paths[] = MU_PLUGINS_DIR . "/views";
  $paths = array_merge($paths, $new_paths);
  return $paths;
});

add_filter(
  "Municipio/controllerPaths",
  function ($paths) {
    $new_paths[] = MUNICIPIO_EXTENDED_PATH . "/psr-4/Controller";
    $new_paths[] = MU_PLUGINS_DIR . "/psr-4/Controller";
    $paths = array_merge($new_paths, $paths);
    return $paths;
  },
  2,
);

add_filter("helsingborg-stad/blade/controllerPaths", function ($paths) {
  $new_paths[] = MUNICIPIO_EXTENDED_PATH . "/psr-4/ComponentLibrary/Component";
  $new_paths[] = MU_PLUGINS_DIR . "/psr-4/ComponentLibrary/Component";
  $paths = array_merge($new_paths, $paths);
  return $paths;
});
