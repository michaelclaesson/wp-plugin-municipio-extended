<?php

add_action("wp_enqueue_scripts", function () {
  $local_css_path = WPMU_PLUGIN_DIR . "/dist/assets/index.css";
  if (file_exists($local_css_path)) {
    wp_enqueue_style(
      "municipio-extended-local",
      WPMU_PLUGIN_URL . "/dist/assets/index.css",
      [],
      filemtime($local_css_path),
    );
  } else {
    wp_enqueue_style(
      "municipio-extended",
      MUNICIPIO_EXTENDED_URL . "/dist/assets/index.css",
      [],
      filemtime(MUNICIPIO_EXTENDED_PATH . "/dist/assets/index.css"),
    );
  }
  wp_enqueue_script(
    "municipio-extended",
    MUNICIPIO_EXTENDED_URL . "/dist/assets/index.js",
    [],
    filemtime(MUNICIPIO_EXTENDED_PATH . "/dist/assets/index.js"),
    true,
  );
});
