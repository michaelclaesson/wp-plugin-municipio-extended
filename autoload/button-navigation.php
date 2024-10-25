<?php

use Kirki;
use Municipio\Customizer;

add_action("article_content_before", function () {
  $post = mx_get_post();
  echo mx_render_view("section-back-button", ["post" => $post]);
});

add_filter(
  "Municipio/Customizer/Sections/General/secondary_navigation_position",
  function ($options) {
    $options["choices"]["below_title"] = _x(
      "Below title",
      "Secondary navigation position",
      "municipio-extended",
    );

    return $options;
  },
);

add_action("article_content_before", function () {
  $value = Kirki::get_option(
    Customizer::KIRKI_CONFIG,
    "secondary_navigation_position",
  );

  if ($value !== "below_title") {
    return null;
  }

  $post = mx_get_post();

  $args = [
    "post_parent" => $post->ID,
    "post_type" => $post->post_type,
    "nopaging" => true,
    "post_status" => "publish",
    "orderby" => "menu_order",
    "order" => "ASC",
    "meta_query" => [
      "relation" => "OR",
      [
        "key" => "hide_in_menu",
        "value" => "1",
        "compare" => "!=",
      ],
      [
        "key" => "hide_in_menu",
        "compare" => "NOT EXISTS",
      ],
    ],
  ];

  $child_posts = get_posts($args);
  $items = array_map(function ($post) {
    return [
      "title" => get_the_title($post->ID),
      "href" => get_permalink($post->ID),
    ];
  }, $child_posts);

  if (!empty($items)) {
    echo '<div class="tailwind">';
    echo mx_render_view("mxui.navigation.buttons", [
      "items" => $items,
    ]);
    echo "</div>";
  }
});
