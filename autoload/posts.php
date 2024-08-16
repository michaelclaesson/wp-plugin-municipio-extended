<?php

/**
 * Puts the archive for posts at /nyheter
 */
add_action("init", function () {
  add_rewrite_rule('^nyheter$', "index.php?category_name=", "top");
});

add_filter(
  "post_type_archive_link",
  function ($link, $post_type) {
    if ($post_type == "post") {
      return home_url("/nyheter/");
    }
    return $link;
  },
  10,
  2,
);

/**
 * Makes sure the first link in the breadcrumbs is always the same
 */
add_filter("Municipio/Breadcrumbs/Items", function ($pageData) {
  array_shift($pageData);
  array_unshift($pageData, [
    "label" => __("Home"),
    "href" => get_home_url(),
    "current" => is_front_page() ? true : false,
    "icon" => "home",
  ]);
  return $pageData;
});

/**
 * Fixes the archive link for posts
 */
add_filter("option_page_for_posts", "__return_null");

add_filter(
  "Modularity/Module/Posts/archiveUrl",
  function ($archive_url, $post_type) {
    if (!$archive_url) {
      $archive_url = get_post_type_archive_link($post_type) ?: false;
    }
    return $archive_url;
  },
  10,
  2,
);
