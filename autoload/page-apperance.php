<?php

use Kirki;
use Municipio\Customizer;

add_action("acf/init", function () {
  add_action(
    "init",
    function () {
      acf_add_local_field([
        "parent" => "group_56c33cf1470dc",
        "key" => "field_page_apperance_theme_color",
        "label" => _x(
          "Theme color",
          "Page Apperance Field Label",
          "municipio-extended",
        ),
        "name" => "page_apperance_theme_color",
        "type" => "color_picker",
        "graphql_field_name" => "themeColor",
        "show_in_graphql" => 1,
        "allow_null" => 1,
      ]);
    },
    20,
  );
});

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

add_action("onepage_content", function () {
  $post = mx_get_post();

  $displayBodyText = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "municipio_customizer_onepage_body_text",
  );

  if ($displayBodyText) {
    echo '<div class="tailwind">';
    echo $post->post_content;
    echo "</div>";
  }
});
