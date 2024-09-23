<?php

use Kirki;
use Municipio\Customizer;

/**
 * Adds customizer fields for the site header.
 */
add_action("init", function () {
  $section_id = "municipio_customizer_section_header";

  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "number",
    "settings" => "header_brand_width",
    "label" => __("Header Logotype Text: Width", "municipio-extended"),
    "default" => 500,
    "choices" => [
      "min" => 0,
    ],
    "priority" => 20,
    "active_callback" => [
      [
        "setting" => "header_brand_enabled",
        "operator" => "==",
        "value" => true,
      ],
    ],
  ]);
});

/**
 * Adds ability to change the width of the header logotype text.
 */
add_filter("ComponentLibrary/Component/Brand/Data", function ($data) {
  $header_brand_width = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "header_brand_width",
  );
  $data["viewBoxWidth"] = $header_brand_width;
  return $data;
});
