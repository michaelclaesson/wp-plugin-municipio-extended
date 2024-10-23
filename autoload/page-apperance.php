<?php

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
