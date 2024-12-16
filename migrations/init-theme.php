<?php

/**
 * 1. Changes to the Municipio theme it is not already active
 * 2. Imports the mods from the main site.
 */

$theme = wp_get_theme();
if ($theme->get_stylesheet() == "municipio") {
  return;
}

$theme = wp_get_theme("municipio");
if (!$theme) {
  throw new Exception("The Municipio theme is not installed");
}

switch_theme($theme->get_stylesheet());

$main_site = get_site(get_main_site_id());
$current_site = get_site(get_current_blog_id());

if ($main_site->blog_id == $current_site->blog_id) {
  return;
}

$main_site_url = get_site_url($main_site->blog_id);

$main_site_theme_mods = file_get_contents(
  $main_site_url . "/wp-admin/admin-ajax.php?action=get_theme_mods",
);

if ($main_site_theme_mods === false) {
  throw new Exception("Failed to get theme mods from the main site");
}

$main_site_theme_mods = json_decode($main_site_theme_mods, true);
if ($main_site_theme_mods === null) {
  throw new Exception("Failed to decode theme mods from the main site");
}

mx_import_theme_mods($main_site_theme_mods);
