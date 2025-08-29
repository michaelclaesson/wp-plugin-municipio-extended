<?php

use Municipio\Customizer;

add_action("init", function () {
  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => "municipio_customizer_section_general",
    "type" => "checkbox_switch",
    "settings" => "municipio_customizer_onepage_body_text",
    "label" => __(
      "Display text content for One Page template",
      "municipio-extended",
    ),
    "default" => false,
    "priority" => 20,
    "output" => [["type" => "controller"]],
  ]);
});

// Add the filter
add_filter("Municipio/Controller/Singular/showTitleOnOnePage", function (
  $current_value,
) {
  $current_value = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "municipio_customizer_onepage_body_text",
  );

  return $current_value;
});
