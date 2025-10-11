<?php

use Kirki\Compatibility\Kirki;

add_action("init", function () {
  $section_id = "municipio_customizer_section_typography";
  Kirki::add_field(\Municipio\Customizer::KIRKI_CONFIG, [
    "type" => "color",
    "settings" => "page_title_color",
    "label" => __("Page title color", "municipio-extended"),
    "description" => __(
      "If no color is selected, the primary color will be used.",
      "municipio-extended",
    ),
    "section" => $section_id,
    "priority" => 10,
    "default" => null,
    "output" => [
      [
        "element" => ":root",
        "property" => "--page-title-color",
      ],
    ],
  ]);
});
