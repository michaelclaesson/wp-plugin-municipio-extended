<?php

use Municipio\Helper\CacheBust as MunicipioCacheBust;

add_action("admin_init", function () {
  // Enqueue dynamic Kirki styles
  add_editor_style(
    add_query_arg(
      [
        "action" => apply_filters("kirki_styles_action_handle", "kirki-styles"),
      ],
      home_url(),
    ),
  );

  // Enqueue material symbols font from Municipio theme (using same cache busting as other assets)
  if (defined("ASSETS_DIST_PATH")) {
    add_editor_style(
      get_template_directory_uri() .
        ASSETS_DIST_PATH .
        MunicipioCacheBust::name("fonts/material-symbols.css"),
    );
  }

  // Enqueue our fixes
  add_editor_style(MUNICIPIO_EXTENDED_URL . "/dist/assets/editor.css");
});
