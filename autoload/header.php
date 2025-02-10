<?php

use Kirki;
use Municipio\Customizer;

/**
 * Adds customizer fields for the site header.
 */
add_action("init", function () {
  $section_id = "municipio_customizer_section_header";

  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "number",
    "settings" => "header_brand_width",
    "label" => __("Header Logotype Text: Width", "municipio-extended"),
    "default" => 500,
    "choices" => [
      "min" => 0,
    ],
    "priority" => 20,
    "active_callback" => [
      [
        "setting" => "header_brand_enabled",
        "operator" => "==",
        "value" => true,
      ],
    ],
  ]);

  Kirki::add_field(Customizer::KIRKI_CONFIG, [
    "section" => $section_id,
    "type" => "select",
    "settings" => "header_breakpoint",
    "label" => __("Header breakpoint", "municipio-extended"),
    "default" => "lg",
    "choices" => [
      "xs" => _x("Extra small", "Screen Breakpoint", "municipio-extended"),
      "sm" => _x("Small", "Screen Breakpoint", "municipio-extended"),
      "md" => _x("Medium", "Screen Breakpoint", "municipio-extended"),
      "lg" => _x("Large", "Screen Breakpoint", "municipio-extended"),
      "xl" => _x("Extra large", "Screen Breakpoint", "municipio-extended"),
    ],
    "priority" => 20,
  ]);
});

/**
 * Adds ability to change the width of the header logotype text.
 */
add_filter("ComponentLibrary/Component/Brand/Data", function ($data) {
  $header_brand_width = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "header_brand_width",
  );
  $data["viewBoxWidth"] = $header_brand_width;
  return $data;
});

add_filter("Municipio/Hook/headerSecondaryNavigationClass", function ($class) {
  $header_breakpoint = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "header_breakpoint",
  );
  $classList = explode(" ", $class);
  // u-display--none@xs u-display--none@sm u-display--none@md
  $classList = array_diff($classList, [
    "u-display--none@xs",
    "u-display--none@sm",
    "u-display--none@md",
  ]);
  $class = clsx($classList, [
    "u-display--none@xs" => in_array($header_breakpoint, [
      "sm",
      "md",
      "lg",
      "xl",
    ]),
    "u-display--none@sm" => in_array($header_breakpoint, ["md", "lg", "xl"]),
    "u-display--none@md" => in_array($header_breakpoint, ["lg", "xl"]),
    "u-display--none@lg" => in_array($header_breakpoint, ["xl"]),
  ]);
  return $class;
});

add_filter("Municipio/Hook/headerSecondaryNavigationTabsClass", function (
  $classList,
) {
  // Remove unwanted classes
  return array_diff($classList, ["u-display--none@md"]);
});

add_filter("Municipio/Hook/headerSearchFormClassList", function ($classList) {
  $header_breakpoint = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "header_breakpoint",
  );
  // search-form u-print-display--none u-display--flex@lg u-display--flex@xl u-display--none@xs u-display--none@sm u-display--none@md
  $classList = array_diff($classList, [
    "u-display--none@xs",
    "u-display--none@sm",
    "u-display--none@md",
  ]);
  $class = clsx($classList, [
    "u-display--none@xs" => in_array($header_breakpoint, [
      "sm",
      "md",
      "lg",
      "xl",
    ]),
    "u-display--none@sm" => in_array($header_breakpoint, ["md", "lg", "xl"]),
    "u-display--none@md" => in_array($header_breakpoint, ["lg", "xl"]),
    "u-display--none@lg" => in_array($header_breakpoint, ["xl"]),
  ]);
  return explode(" ", $class);
});

add_filter("Municipio/Hook/primaryNavigationClass", function ($class) {
  $classList = explode(" ", $class);
  // u-display--none@xs u-display--none@sm u-display--none@md u-print-display--none
  $classList = array_diff($classList, [
    "u-display--none@xs",
    "u-display--none@sm",
    "u-display--none@md",
    "u-print-display--none",
  ]);
  $class = clsx($classList, [
    // "u-display--none@xs",
    // "u-display--none@sm",
    // "u-display--none@md",
  ]);
  return $class;
});

add_filter("Municipio/Hook/mobileSearchFormClassList", function ($classList) {
  $header_breakpoint = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "header_breakpoint",
  );
  // c-form search-form u-padding__y--2 u-padding__x--3 u-width--auto u-display--none@lg u-display--none@xl u-print-display--none js-form-validation
  $classList = array_diff($classList, [
    "u-display--none@lg",
    "u-display--none@xl",
  ]);
  $class = clsx($classList, [
    "u-display--none@xs" => in_array($header_breakpoint, ["xs"]),
    "u-display--none@sm" => in_array($header_breakpoint, ["xs", "sm"]),
    "u-display--none@md" => in_array($header_breakpoint, ["xs", "sm", "md"]),
    "u-display--none@lg" => in_array($header_breakpoint, [
      "xs",
      "sm",
      "md",
      "lg",
    ]),
    "u-display--none@xl" => in_array($header_breakpoint, [
      "xs",
      "sm",
      "md",
      "lg",
      "xl",
    ]),
  ]);
  return explode(" ", $class);
});
