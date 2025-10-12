<?php

use Kirki\Compatibility\Kirki;

add_action("init", function () {
  $section_id = "municipio_customizer_section_general";

  Kirki::add_field(\Municipio\Customizer::KIRKI_CONFIG, [
    "type" => "checkbox",
    "settings" => "show_print_button",
    "label" => __("Show print button", "municipio-extended"),
    "section" => $section_id,
    "priority" => 50,
    "default" => true,
  ]);
});

add_filter("Municipio/Accessibility/Items", function ($items) {
  $show_print_button = get_theme_mod("show_print_button", true);
  if (!$show_print_button && isset($items["print"])) {
    unset($items["print"]);
  }
  return $items;
});
