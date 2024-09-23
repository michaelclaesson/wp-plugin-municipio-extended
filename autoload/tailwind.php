<?php

// use Kirki;
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
