<?php

// Add Alert post type
add_action("init", function () {
  $labels = [
    "name" => _x("Alerts", "Post Type General Name", "municipio-extended"),
    "singular_name" => _x(
      "Alert",
      "Post Type Singular Name",
      "municipio-extended",
    ),
    "menu_name" => _x("Alerts", "Admin Menu text", "municipio-extended"),
    "name_admin_bar" => _x(
      "Alerts",
      "Add New on Toolbar",
      "municipio-extended",
    ),
    "add_new" => __("Add new alert", "municipio-extended"),
    "add_new_item" => __("Add new alert", "municipio-extended"),
    "new_item" => __("New alert", "municipio-extended"),
    "edit_item" => __("Edit alert", "municipio-extended"),
    "view_item" => __("View alert", "municipio-extended"),
    "all_items" => __("All alerts", "municipio-extended"),
    "not_found" => __("No alerts found.", "municipio-extended"),
  ];

  register_post_type("alert", [
    "label" => _x("Alerts", "Post Type General Name", "municipio-extended"),
    "labels" => $labels,
    "public" => false,
    "show_ui" => true,
    "show_in_menu" => true,
    "show_in_nav_menus" => false,
    "show_in_admin_bar" => false,
    "show_in_rest" => false,
    "exclude_from_search" => false,
    "supports" => ["title", "editor"],
    "menu_icon" => "dashicons-megaphone",
  ]);
});

// Add icon field to Alert posts
add_action("acf/init", function () {
  if (function_exists("acf_add_local_field_group")):
    acf_add_local_field_group([
      "key" => "group_alert_settings",
      "title" => _x(
        "Alert Settings",
        "Field Group Title",
        "municipio-extended",
      ),
      "fields" => [
        [
          "key" => "field_alert_type",
          "label" => _x(
            "Type",
            "Alert Settings Field Label",
            "municipio-extended",
          ),
          "name" => "alert_type",
          "type" => "select",
          "choices" => [
            "success" => _x(
              "Success",
              "Alert Type Choice",
              "municipio-extended",
            ),
            "info" => _x("Info", "Alert Type Choice", "municipio-extended"),
            "warning" => _x(
              "Warning",
              "Alert Type Choice",
              "municipio-extended",
            ),
            "danger" => _x("Danger", "Alert Type Choice", "municipio-extended"),
          ],
          "default_value" => "info",
          "allow_null" => 0,
          "multiple" => 0,
          "ui" => 0,
          "return_format" => "value",
          "ajax" => 0,
          "placeholder" => "",
        ],
      ],
      "location" => [
        [
          [
            "param" => "post_type",
            "operator" => "==",
            "value" => "alert",
          ],
        ],
      ],
      "position" => "normal",
      "style" => "default",
    ]);
  endif;
});

add_action("above_header", function () {
  $alerts = get_posts([
    "post_type" => "alert",
  ]);

  if (!empty($alerts)) {
    $icons = [
      "info" => "info",
      "success" => "check_circle",
      "warning" => "warning",
      "danger" => "error",
    ];

    echo '<div class="tailwind">';
    foreach ($alerts as $alert) {
      $type = get_field("alert_type", $alert->ID);
      $alert_data = [
        "title" => $alert->post_title,
        "content" => $alert->post_content,
        "type" => $type,
        "icon" => $icons[$type] ?? "info",
      ];
      echo mx_render_view("mxui.alert", $alert_data);
    }
    echo "</div>";
  }
});
