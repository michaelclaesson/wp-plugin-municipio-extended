<?php

use Kirki;
use Municipio\Customizer;

/**
 * Adds a customizer section for MXUI components.
 */
add_action("init", function () {
  $section_id = "municipio_customizer_section_component_card";

  Kirki::add_section($section_id, [
    "panel" => "municipio_customizer_panel_design_component",
    "title" => __("Cards", "municipio-extended"),
    "priority" => 170,
  ]);

  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "checkbox",
    "settings" => "card_mxui_enabled",
    "label" => __("Use MXUI version", "municipio-extended"),
    "default" => false,
  ]);

  $section_id = "municipio_customizer_section_header";

  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "select",
    "settings" => "main_menu_style",
    "label" => __("Style for main menu", "municipio-extended"),
    "default" => 'standard',
    'priority'    => 10,
    'choices'     => [
      'standard' => __('Standard', 'municipio-extended'),
      'compact' => __('Compact', 'municipio-extended'),
  ],
  'output' => [
      ['type' => 'controller']
  ],
  ]);
});
/**
 * Overrides the default way of setting placeholder images on cards.
 */
function mxui_component_data_emblem_filter_cb($data) {
  if (!empty($data["hasPlaceholder"]) && $data["hasPlaceholder"] === true) {
    if (!is_array($data["image"])) {
      $data["image"] = [];
    }
    $logotypeEmblem = Kirki::get_option(
      Customizer::KIRKI_CONFIG,
      "logotype_emblem",
    );
    if ($logotypeEmblem) {
      $data["image"]["src"] = $logotypeEmblem;
    } else {
      $data["image"] = null;
    }
    $data["hasPlaceholder"] = false; // Prevents next filter from changing this
  }
  return $data;
}
add_filter(
  "ComponentLibrary/Component/Card/Data",
  "mxui_component_data_emblem_filter_cb",
  9, // Run before Municipio default filter
  1,
);
add_filter(
  "ComponentLibrary/Component/Block/Data",
  "mxui_component_data_emblem_filter_cb",
  9, // Run before Municipio default filter
  1,
);
add_filter(
  "ComponentLibrary/Component/Segment/Data",
  "mxui_component_data_emblem_filter_cb",
  9, // Run before Municipio default filter
  1,
);
