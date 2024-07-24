<?php

add_action("acf/init", function () {
  add_action(
    "init",
    function () {
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
    },
    20,
  );
});
