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
        $wp_query->is_404 = false;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $post = $custom_404_page;

        setup_postdata($post);

        include get_page_template();
        exit();
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
