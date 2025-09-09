<?php

use Kirki\Compatibility\Kirki;
use Municipio\Customizer;

add_action("acf/init", function () {
  add_action(
    "init",
    function () {
      $section_start_page_enabled = get_theme_mod(
        "section_start_page_enabled",
        false,
      );

      acf_add_local_field([
        "parent" => "group_56d83cff12bb3",
        "key" => "field_page_navigation_description",
        "label" => _x(
          "Description",
          "Page Navigation Field Label",
          "municipio-extended",
        ),
        "name" => "page_navigation_description",
        "type" => "textarea",
        "graphql_field_name" => "description",
        "show_in_graphql" => 1,
        "instructions" => __(
          "A short description of the page to show in menus.",
          "municipio-extended",
        ),
        "rows" => 2,
        "conditional_logic" => [
          [
            [
              "field" => "field_56d83d2777785",
              "operator" => "!=",
              "value" => 1,
            ],
          ],
        ],
      ]);

      acf_add_local_field([
        "parent" => "group_56d83cff12bb3",
        "key" => "field_page_navigation_icon",
        "label" => _x(
          "Icon",
          "Page Navigation Field Label",
          "municipio-extended",
        ),
        "name" => "page_navigation_icon",
        "type" => "select",
        "graphql_field_name" => "icon",
        "show_in_graphql" => 1,
        "conditional_logic" => [
          [
            [
              "field" => "field_56d83d2777785",
              "operator" => "!=",
              "value" => 1,
            ],
          ],
        ],
        "choices" => array_combine(
          mx_get_material_icons(),
          mx_get_material_icons(),
        ),
        "ui" => 1,
        "allow_null" => 1,
      ]);

      if ($section_start_page_enabled) {
        acf_add_local_field([
          "parent" => "group_56d83cff12bb3",
          "key" => "field_page_navigation_section_start_page",
          "label" => _x(
            "Section start page",
            "Page Navigation Field Label",
            "municipio-extended",
          ),
          "name" => "page_navigation_section_start_page",
          "type" => "true_false",
          "graphql_field_name" => "sectionStartPage",
          "show_in_graphql" => 1,
          "default_value" => 0,
          "ui" => 1,
          "ui_on_text" => "",
          "ui_off_text" => "",
        ]);
      }

      $secondary_menu_position =
        Kirki::get_option(
          Customizer::KIRKI_CONFIG,
          "secondary_navigation_position",
        ) ?:
        "left";

      acf_add_local_field([
        "parent" => "group_56d83cff12bb3",
        "key" => "field_page_hide_secondary_menu",
        "label" => _x(
          "Hide secondary menu",
          "Page Navigation Field Label",
          "municipio-extended",
        ),
        "name" => "page_hide_secondary_menu",
        "type" => "true_false",
        "ui" => 1,
        "wrapper" => [
          // Hide the field if the secondary menu is hidden globally
          "style" =>
            $secondary_menu_position == "hidden" ? "display:none;" : null,
        ],
        "instructions" =>
          [
            "left" => __("The menu in the left sidebar", "municipio-extended"),
            "right" => __(
              "The menu in the right sidebar",
              "municipio-extended",
            ),
          ][$secondary_menu_position] ?? "",
        "conditional_logic" => 0,
      ]);
    },
    20,
  );
});

add_filter("Municipio/Template/viewData", function ($viewData) {
  if (get_field("page_hide_secondary_menu")) {
    unset($viewData["secondaryMenuItems"]);
  }
  return $viewData;
});
