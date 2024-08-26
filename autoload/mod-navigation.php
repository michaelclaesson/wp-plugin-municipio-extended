<?php

add_filter(
  "/Modularity/externalViewPath",
  function ($paths) {
    $paths["mod-navigation"] = MUNICIPIO_EXTENDED_PATH . "/views";
    return $paths;
  },
  2,
  1,
);

add_action("plugins_loaded", function ($array) {
  if (function_exists("modularity_register_module")) {
    modularity_register_module(
      MUNICIPIO_EXTENDED_PATH . "/psr-4/Modularity/ModNavigation",
      "ModNavigation",
    );
  }
});

function get_all_menus() {
  $menus = wp_get_nav_menus();
  $choices = [];

  foreach ($menus as $menu) {
    $choices[$menu->slug] = $menu->name;
  }

  return $choices;
}

add_action("acf/init", function () {
  acf_add_local_field_group([
    "key" => "group_mod_navigation",
    "title" => _x(
      "Navigation module",
      "Navigation Module Field Group Title",
      "municipio-extended",
    ),
    "fields" => [
      [
        "key" => "field_mod_navigation_format",
        "label" => _x(
          "Format",
          "Navigation Module Field Label",
          "municipio-extended",
        ),
        "name" => "mod_navigation_format",
        "graphql_field_name" => "format",
        "show_in_graphql" => 1,
        "type" => "select",
        "required" => 1,
        "return_format" => "value",
        "choices" => [
          "list" => _x(
            "List",
            "Navigation Module Format Choice",
            "municipio-extended",
          ),
          "grid" => _x(
            "Grid",
            "Navigation Module Format Choice",
            "municipio-extended",
          ),
          "bar" => _x(
            "Bar",
            "Navigation Module Format Choice",
            "municipio-extended",
          ),
          "tree" => _x(
            "Tree",
            "Navigation Module Format Choice",
            "municipio-extended",
          ),
        ],
      ],
      [
        "key" => "field_mod_navigation_source",
        "label" => _x(
          "Source",
          "Navigation Module Field Label",
          "municipio-extended",
        ),
        "name" => "mod_navigation_source",
        "graphql_field_name" => "source",
        "show_in_graphql" => 1,
        "type" => "select",
        "required" => 0,
        "return_format" => "value",
        "choices" => [
          "children" => _x(
            "Child pages",
            "Navigation Module Source Choice",
            "municipio-extended",
          ),
          "siblings" => _x(
            "Sibling pages",
            "Navigation Module Source Choice",
            "municipio-extended",
          ),
          "menu" => _x(
            "Menu",
            "Navigation Module Source Choice",
            "municipio-extended",
          ),
          "manual" => _x(
            "Manually selected",
            "Navigation Module Source Choice",
            "municipio-extended",
          ),
        ],
      ],
      [
        "key" => "field_mod_navigation_menu",
        "label" => _x(
          "Menu",
          "Navigation Module Field Label",
          "municipio-extended",
        ),
        "name" => "mod_navigation_menu",
        "graphql_field_name" => "menu",
        "show_in_graphql" => 1,
        "type" => "select",
        "return_format" => "value",
        "choices" => get_all_menus(),
        "allow_null" => 1,
        "conditional_logic" => [
          [
            [
              "field" => "field_mod_navigation_source",
              "operator" => "==",
              "value" => "menu",
            ],
          ],
        ],
      ],
      [
        "key" => "field_mod_navigation_items",
        "label" => _x(
          "Items",
          "Navigation Module Field Label",
          "municipio-extended",
        ),
        "name" => "mod_navigation_items",
        "graphql_field_name" => "items",
        "show_in_graphql" => 1,
        "type" => "repeater",
        "conditional_logic" => [
          [
            [
              "field" => "field_mod_navigation_source",
              "operator" => "==",
              "value" => "manual",
            ],
          ],
        ],
        "sub_fields" => [
          [
            "key" => "field_mod_navigation_item_link",
            "label" => _x(
              "Link",
              "Navigation Module Field Label",
              "municipio-extended",
            ),
            "name" => "link",
            "type" => "link",
            "required" => 1,
            "wrapper" => ["width" => "75%"],
          ],
          [
            "key" => "field_mod_navigation_color",
            "label" => _x(
              "Color",
              "Navigation Module Field Label",
              "municipio-extended",
            ),
            "name" => "color",
            "type" => "color_picker",
            "wrapper" => ["width" => "25%"],
            "conditional_logic" => [
              [
                [
                  "field" => "field_mod_navigation_format",
                  "operator" => "==",
                  "value" => "grid",
                ],
              ],
            ],
          ],
          [
            "key" => "field_mod_navigation_item_icon",
            "label" => _x(
              "Icon",
              "Navigation Module Field Label",
              "municipio-extended",
            ),
            "name" => "icon",
            "type" => "select",
            "choices" => array_combine(
              mx_get_material_icons(),
              mx_get_material_icons(),
            ),
            "ui" => 1,
            "allow_null" => 1,
            "wrapper" => ["width" => "25%"],
            "conditional_logic" => [
              [
                [
                  "field" => "field_mod_navigation_format",
                  "operator" => "!=",
                  "value" => "tree",
                ],
              ],
            ],
          ],
        ],
      ],
      // [
      //   "key" => "field_mod_navigation_depth",
      //   "label" => _x("Depth", "Navigation Module Field Label", "municipio-extended"),
      //   "name" => "mod_navigation_depth",
      //   "graphql_field_name" => "depth",
      //   "show_in_graphql" => 1,
      //   "type" => "number",
      //   "required" => 0,
      //   "default_value" => 1,
      //   "min" => 1,
      //   "conditional_logic" => [
      //     [
      //       [
      //         "field" => "field_mod_navigation_source",
      //         "operator" => "==",
      //         "value" => "children",
      //       ],
      //     ],
      //   ],
      // ],
    ],
    "location" => [
      [
        [
          "param" => "post_type",
          "operator" => "==",
          "value" => "mod-navigation",
        ],
      ],
    ],
    "show_in_graphql" => 1,
  ]);
});
