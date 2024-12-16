<?php

/**
 * Adds an ajax action to get all theme mods
 */

function mx_ajax_get_theme_mods_handler() {
  $mods = get_theme_mods();
  wp_send_json($mods);
}
add_action("wp_ajax_get_theme_mods", "mx_ajax_get_theme_mods_handler");
add_action("wp_ajax_nopriv_get_theme_mods", "mx_ajax_get_theme_mods_handler");

function mx_import_theme_mods($mods) {
  foreach ($mods as $key => $value) {
    if (in_array($key, [0, "nav_menu_locations", "sidebars_widgets"])) {
      continue;
    }
    set_theme_mod($key, $value);
  }
}
