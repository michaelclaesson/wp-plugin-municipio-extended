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
});
