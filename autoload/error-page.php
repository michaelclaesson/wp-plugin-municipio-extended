<?php

function mx_get_custom_404_page() {
  static $custom_page;
  if ($custom_page) {
    return $custom_page;
  }
  $custom_page = get_page_by_path("page-not-found");
  return $custom_page;
}

function is_custom_404() {
  $custom_404_page = mx_get_custom_404_page();
  return $custom_404_page && $custom_404_page->ID === get_queried_object_id();
}

add_action(
  "template_redirect",
  function () {
    if (is_404()) {
      $custom_404_page = mx_get_custom_404_page();
      if ($custom_404_page) {
        // Reset query flags so that WordPress treats this as a regular page.
        global $wp_query, $post;

        $original_post = $post;
        $original_is_404 = $wp_query->is_404;
        $original_is_page = $wp_query->is_page;
        $original_is_singular = $wp_query->is_singular;
        $original_queried_object_id = $wp_query->queried_object_id;
        $original_post_count = $wp_query->post_count;
        $original_current_post = $wp_query->current_post;
        $original_posts = $wp_query->posts;

        $wp_query->is_404 = false;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->queried_object_id = $post->ID;
        $wp_query->post_count = 1;
        $wp_query->current_post = -1;
        $wp_query->posts = [$post];
        $post = $custom_404_page;

        setup_postdata($post);

        $file = get_page_template() ?: get_query_template("page");

        if (file_exists($file)) {
          include $file;
          exit();
        }

        $post = $original_post;
        $wp_query->is_404 = $original_is_404;
        $wp_query->is_page = $original_is_page;
        $wp_query->is_singular = $original_is_singular;
        $wp_query->queried_object_id = $original_queried_object_id;
        $wp_query->post_count = $original_post_count;
        $wp_query->current_post = $original_current_post;
        $wp_query->posts = $original_posts;

        setup_postdata($original_post);
      }
    }
  },
  9,
);

add_action(
  "init",
  function () {
    \Municipio\Helper\Template::add(
      __("Error page", "municipio-extended"),
      \Municipio\Helper\Template::locateTemplate("error-template.blade.php"),
      "all",
    );
  },
  11,
);
