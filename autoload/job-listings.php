<?php

use Kirki;
use Municipio\Customizer;

add_filter(
  "Municipio/viewData",
  function ($data) {
    if (
      $data["isSingular"] &&
      ($data["post"]->postType ?? "") == "job-listing"
    ) {
      $articleContentBefore = $data["hook"]->articleContentBefore;
      $articleContentBefore .= '<div class="tailwind">';
      $articleContentBefore .= mx_render_view(
        "mxui.job-listing.article-content-before",
        $data,
      );
      $articleContentBefore .= "</div>";
      $data["hook"]->articleContentBefore = $articleContentBefore;
    }
    return $data;
  },
  20,
);

add_filter(
  "Municipio/Customizer/Sections/Archive/archiveStyleChoices",
  function ($choices) {
    $choices["table"] = __("Table", "municipio-extended");
    return $choices;
  },
);

add_action(
  "Municipio/Customizer/Sections/Archive/init",
  function ($section_id, $archive) {
    Kirki::add_field(\Municipio\Customizer::KIRKI_CONFIG, [
      "type" => "select",
      "settings" => "archive_" . $archive->name . "_metas_to_display",
      "label" => esc_html__("Meta display", "municipio"),
      // 'description' => esc_html__('What meta fields', 'municipio'),
      "multiple" => 4,
      "section" => $section_id,
      "choices" => $archive->dateSource,
      // Below prevents Kirki bugg from using faulty default sanitize_callback.
      "sanitize_callback" => fn($values) => $values,
      "output" => [
        [
          "type" => "controller",
          "as_object" => true,
        ],
      ],
    ]);
  },
  10,
  2,
);

add_filter("Municipio/Helper/Post/postObject", function ($postObject) {
  $fields = get_theme_mod(
    "archive_" . get_post_type($postObject->ID) . "_metas_to_display",
    false,
  );

  $values = [];
  if (is_array($fields) && !empty($fields)) {
    foreach ($fields as $field) {
      $value = get_post_meta($postObject->ID, $field, true);
      if (!empty($value)) {
        $item = [];
        $item["value"] = $value;
        $item["field"] = $field;
        $values[] = $item;
      }
    }
  }
  $postObject->metaValues = $values;
  return $postObject;
});

add_filter(
  "mx/meta_field/label",
  function ($label, $field) {
    switch ($field->field) {
      case "application_end_date":
        return _x(
          "Application end date",
          "Post Meta Field Label",
          "municipio-extended",
        );
      case "publish_start_date":
        return _x(
          "Publish start date",
          "Post Meta Field Label",
          "municipio-extended",
        );
    }
    return $label;
  },
  10,
  2,
);
