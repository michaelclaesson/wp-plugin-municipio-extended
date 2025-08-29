<?php
use Municipio\Customizer;

// add_filter("EventManagerIntegration/DisableEventHero", "__return_true");
add_filter("EventManagerIntegration/DisableEventHeroOverlay", function () {
  return Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "disable_event_hero_overlay",
  );
});

add_action("init", function () {
  $post_type = "event";
  $section_id = "municipio_customizer_panel_content_types_{$post_type}";
  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "checkbox",
    "settings" => "disable_event_hero_overlay",
    "label" => __("Disable event hero overlay", "municipio-extended"),
    "default" => false,
    "priority" => 20,
  ]);
});

add_filter(
  "register_taxonomy_args",
  function ($args, $taxonomy) {
    if ($taxonomy === "event_groups") {
      $args["exclude_from_logs"] = true;
    }
    return $args;
  },
  10,
  2,
);
