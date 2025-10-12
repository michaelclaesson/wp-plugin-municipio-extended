<?php

use Kirki\Compatibility\Kirki;

function mx_prosify($content, $options = []) {
  $content = preg_replace_callback(
    '/(<a\s[^>]*class="((?:[^"]+\s)c-button)([^"]*)"\s[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>)/',
    function ($matches) {
      $classes = preg_split("/\s+/", $matches[2]);
      $href = $matches[4];
      $label = strip_tags($matches[5]);
      return mx_render_view("mxui.button", [
        "href" => $href,
        "label" => $label,
        "variant" => in_array("c-button__outlined", $classes)
          ? "outlined"
          : (in_array("c-button__filled--primary", $classes)
            ? "primary"
            : (in_array("c-button__filled--secondary", $classes)
              ? "secondary"
              : "default")),
      ]);
    },
    $content,
  );
  return $content;
}

add_filter(
  "Municipio/Customizer/Sections/Button/primary/kirkiFieldArgs",
  function ($args) {
    $args["choices"]["hover"] = esc_html__("Hover", "municipio-extended");
    $args["default"]["hover"] =
      Kirki::get_option("color_palette_primary")["dark"] ?? "#ddd";
    $args["output"][] = [
      "choice" => "hover",
      "element" => ":root",
      "property" => "--c-button-primary-color-hover",
    ];

    $args["default"]["hover_contrasting"] =
      Kirki::get_option("color_palette_primary")["contrasting"] ?? "#000";
    $args["choices"]["hover_contrasting"] = esc_html__(
      "Hover contrasting",
      "municipio-extended",
    );
    $args["output"][] = [
      "choice" => "hover_contrasting",
      "element" => ":root",
      "property" => "--c-button-primary-color-hover-contrasting",
    ];
    return $args;
  },
);

add_filter(
  "Municipio/Customizer/Sections/Button/secondary/kirkiFieldArgs",
  function ($args) {
    $args["choices"]["hover"] = esc_html__("Hover", "municipio-extended");
    $args["default"]["hover"] =
      Kirki::get_option("color_palette_secondary")["dark"] ?? "#ddd";
    $args["output"][] = [
      "choice" => "hover",
      "element" => ":root",
      "property" => "--c-button-secondary-color-hover",
    ];

    $args["default"]["hover_contrasting"] =
      Kirki::get_option("color_palette_secondary")["contrasting"] ?? "#000";
    $args["choices"]["hover_contrasting"] = esc_html__(
      "Hover contrasting",
      "municipio-extended",
    );
    $args["output"][] = [
      "choice" => "hover_contrasting",
      "element" => ":root",
      "property" => "--c-button-secondary-color-hover-contrasting",
    ];
    return $args;
  },
);

add_filter(
  "Municipio/Customizer/Sections/Button/default/kirkiFieldArgs",
  function ($args) {
    $args["choices"]["hover"] = esc_html__("Hover", "municipio-extended");
    $args["default"]["hover"] =
      Kirki::get_option("color_palette_default")["dark"] ?? "#ddd";
    $args["output"][] = [
      "choice" => "hover",
      "element" => ":root",
      "property" => "--c-button-color-hover",
    ];

    $args["default"]["hover_contrasting"] =
      Kirki::get_option("color_palette_default")["contrasting"] ?? "#000";
    $args["choices"]["hover_contrasting"] = esc_html__(
      "Hover contrasting",
      "municipio-extended",
    );
    $args["output"][] = [
      "choice" => "hover_contrasting",
      "element" => ":root",
      "property" => "--c-button-color-hover-contrasting",
    ];
    return $args;
  },
);
