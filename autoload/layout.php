<?php

use Kirki\Compatibility\Kirki;
use Municipio\Customizer;
use Modularity\Editor;

add_action("init", function () {
  $section_id = "municipio_customizer_section_width";
  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "type" => "custom",
    "settings" => "mx_section_content_layout_heading",
    "section" => $section_id,
    "default" =>
      "<h2>" .
      esc_html_x(
        "Content layout",
        "Customizer Section Width Heading",
        "municipio-extended",
      ) .
      "</h2>",
    "priority" => 50,
  ]);
  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "select",
    "settings" => "mx_default_module_width",
    "label" => __("Default module width", "municipio-extended"),
    "description" => __(
      "Select the width modules should have when set to “inherit”.",
      "municipio-extended",
    ),
    "default" => "grid-md-12",
    "choices" => Editor::widthOptions(),
    "priority" => 50,
  ]);
  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "radio",
    "settings" => "mx_content_area_placement",
    "label" => __("Content area placement", "municipio-extended"),
    "description" => __(
      "Select how modules in the “%s” module area are placed in relation to the article.",
      "municipio-extended",
    ),
    "default" => "outside",
    "choices" => [
      "outside" => _x(
        "Outside",
        "Content Area Placement Option",
        "municipio-extended",
      ),
      "inside" => _x(
        "Inside",
        "Content Area Placement Option",
        "municipio-extended",
      ),
    ],
    "priority" => 50,
  ]);
  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "radio",
    "settings" => "mx_article_alignment",
    "label" => __("Article content alignment", "municipio-extended"),
    "description" => __(
      "Select how the article content should be aligned.",
      "municipio-extended",
    ),
    "default" => "left",
    "choices" => [
      "left" => _x(
        "Left",
        "Article Content Alignment Option",
        "municipio-extended",
      ),
      "center" => _x(
        "Center",
        "Article Content Alignment Option",
        "municipio-extended",
      ),
    ],
    "priority" => 50,
  ]);
});

add_filter(
  "Modularity/Display/BeforeModule::widthClass",
  function ($width_class, $module) {
    $width = $module->columnWidth ?? "";
    if (empty($width)) {
      $default_width = get_theme_mod("mx_default_module_width", "grid-md-12");
      if (!empty($default_width)) {
        $width_class = $default_width;
      }
    }
    return $width_class;
  },
  10,
  2,
);

add_filter("Municipio/views/single/content-area/show", function ($show) {
  $placement = get_theme_mod("mx_content_area_placement", "outside");
  return $show && $placement === "outside";
});

add_filter("Municipio/views/page-centered/content-area/show", function ($show) {
  $placement = get_theme_mod("mx_content_area_placement", "outside");
  return $show && $placement === "outside";
});
