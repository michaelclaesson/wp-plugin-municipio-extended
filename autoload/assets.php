<?php

add_action("wp_enqueue_scripts", function () {
  wp_enqueue_style(
    "municipio-extended",
    MUNICIPIO_EXTENDED_URL . "/dist/assets/index.css",
    [],
    filemtime(MUNICIPIO_EXTENDED_PATH . "/dist/assets/index.css"),
  );
  wp_enqueue_script(
    "municipio-extended",
    MUNICIPIO_EXTENDED_URL . "/dist/assets/index.js",
    [],
    filemtime(MUNICIPIO_EXTENDED_PATH . "/dist/assets/index.js"),
    true,
  );

  if (file_exists(MU_PLUGINS_DIR . "/dist/assets/index.css")) {
    wp_enqueue_style(
      "mu-plugin",
      MU_PLUGINS_URL . "/dist/assets/index.css",
      [],
      filemtime(MU_PLUGINS_DIR . "/dist/assets/index.css"),
    );
  }
  if (file_exists(MU_PLUGINS_DIR . "/dist/assets/index.js")) {
    wp_enqueue_script(
      "mu-plugin",
      MU_PLUGINS_URL . "/dist/assets/index.js",
      [],
      filemtime(MU_PLUGINS_DIR . "/dist/assets/index.js"),
      true,
    );
  }
});
