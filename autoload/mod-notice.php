<?php

acf_add_local_field([
  "parent" => "group_575a842dd1283",
  "key" => "field_notice_icon",
  "label" => _x("Icon", "Notice Module Field Icon", "municipio-extended"),
  "name" => "notice_icon",
  "type" => "select",
  "graphql_field_name" => "noticeIcon",
  "show_in_graphql" => 1,
  "conditional_logic" => 0,
  "wrapper" => ["width" => "50%"],
  "choices" => [],
  "ui" => 1,
  "allow_null" => 1,
]);

add_filter("acf/load_field/key=field_notice_icon", function ($field) {
  return mx_add_icons_list($field);
});

function mx_add_icons_list($field): array {
  $materialIcons = mx_get_material_icons();

  if (is_array($materialIcons) && !empty($materialIcons)) {
    foreach ($materialIcons as $materialIcon) {
      $field["choices"][$materialIcon] =
        '<i class="material-symbols-outlined" style="float: left;">' .
        $materialIcon .
        '</i> <span style="height: 24px; display: inline-block; line-height: 24px; margin-left: 8px;">' .
        str_replace("_", " ", $materialIcon) .
        "</span>";
    }
  }

  if (empty($field["choices"])) {
    $field["choices"] = [];
  }

  return $field;
}

add_filter("Modularity/Display/mod-notice/viewData", function ($data) {
  if (!empty($data["notice_icon"])) {
    $data["icon"] = [
      "name" => $data["notice_icon"],
    ];
  }
  return $data;
});
