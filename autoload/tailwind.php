<?php

// use Kirki\Compatibility\Kirki;
// use Municipio\Customizer;

// function mx_is_white($color) {
//   return in_array($color, ["#ffffff", "#fff", "white", "rgb(255, 255, 255)"]);
// }

add_filter("Municipio/bodyClass", function ($class) {
  // $color_background = Kirki::get_option(
  //   Customizer::KIRKI_CONFIG,
  //   "color_background",
  // );
  // $color_background = Kirki::get_option(
  //   Customizer::KIRKI_CONFIG,
  //   "color_background",
  // );
  // $is_white = mx_is_white($color_background['background'] ?? );
  // $classList = [$is_white ? 'layer-white':"layer-neutral", $class];
  $classList = ["layer-card", $class];
  return clsx($classList);
});

function mx_grid_cols_class($cols) {
  switch ($cols) {
    case 1:
      return "grid-cols-1";
    case 2:
      return "grid-cols-2";
    case 3:
      return "grid-cols-3";
    case 4:
      return "grid-cols-4";
    case 5:
      return "grid-cols-5";
    case 6:
      return "grid-cols-6";
    case 7:
      return "grid-cols-7";
    case 8:
      return "grid-cols-8";
    case 9:
      return "grid-cols-9";
    case 10:
      return "grid-cols-10";
    case 11:
      return "grid-cols-11";
    case 12:
      return "grid-cols-12";
  }
}
