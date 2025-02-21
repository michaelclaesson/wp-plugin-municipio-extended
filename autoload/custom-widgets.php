<?php
function register_custom_widget_area() {
  register_sidebar([
    "id" => "related-pages-area",
    "name" => __("Related pages sidebar", "municipio-extended"),
    "description" => __(
      "Widget area made for related pages",
      "municipio-extended",
    ),
    "before_title" => '<h2 class="c-typography c-typography__variant--h3">',
    "after_title" => "</h2>",
    "before_widget" => '<div id="%1$s" class="%2$s">',
    "after_widget" => "</div>",
  ]);
  register_sidebar([
    "id" => "under-hero",
    "name" => __("Under hero sidebar", "municipio-extended"),
    "description" => __(
      "Widget area made for under hero",
      "municipio-extended",
    ),
    "before_title" => '<h2 class="c-typography c-typography__variant--h3">',
    "after_title" => "</h2>",
    "before_widget" => '<div id="%1$s" class="%2$s">',
    "after_widget" => "</div>",
  ]);
}
add_action("widgets_init", "register_custom_widget_area");
