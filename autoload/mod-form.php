<?php

use Municipio\Customizer;

/**
 * Adds a customizer section for the Form module.
 */
add_action("init", function () {
  $section_id = "municipio_customizer_section_module_mod_form";

  Kirki::add_section($section_id, [
    "panel" => "municipio_customizer_panel_design_module",
    "title" => __("Form", "municipio-extended"),
    "priority" => 170,
  ]);

  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "editor",
    "settings" => "mod_form_gdpr_complience_notice_content",
    "label" => __("Default content for GDPR notice", "municipio-extended"),
    "default" =>
      "I det här formuläret samlar vi in personuppgifter om dig för att [det formuläret ska användas till]. Uppgifterna kommer inte att hanteras för något annat syfte, och kommer att raderas när [ärendet är avslutat].",
  ]);
});

/**
 * Replaces the default value of the GDPR compliance notice field with the value
 * from the customizer.
 */
add_filter("acf/load_field/key=field_5b3c8e0a6e7f2", function ($field) {
  $field["default_value"] =
    Kirki::get_option(
      Customizer::KIRKI_CONFIG,
      "mod_form_gdpr_complience_notice_content",
    ) ?:
    "";
  return $field;
});
