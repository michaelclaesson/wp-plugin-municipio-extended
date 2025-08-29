<?php

use Municipio\Customizer;

/**
 * Adds a "Use improved appearance" checkbox in the customizer section for
 * Contacts module.
 */
// TODO: Remove this when all clients are using it.
add_action(
  "init",
  function () {
    $section_id = "municipio_customizer_section_mod_contacts";

    Kirki::add_field(Customizer::KIRKI_CONFIG, [
      "section" => $section_id,
      "type" => "checkbox",
      "settings" => "mx_mod_contacts_use_improved_appearance",
      "label" => __("Use improved appearance", "municipio-extended"),
      "default" => false,
    ]);
  },
  12, // Right after module initialization.
);

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug) {
    if ($slug === "contacts") {
      $use_improved_appearance = get_theme_mod(
        "mx_mod_contacts_use_improved_appearance",
        false,
      );
      if ($use_improved_appearance) {
        $template = "mod-contacts.blade.php";
      }
    }
    return $template;
  },
  10,
  2,
);

// Modify social media choices to change Twitter to X
add_filter("acf/load_field", function ($field) {
  if ($field["key"] === "field_5bf6a737c1b6c") {
    if (isset($field["choices"]["twitter"])) {
      $field["choices"]["twitter"] = "X";
    }
  }
  return $field;
});
