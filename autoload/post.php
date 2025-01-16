<?php

add_filter(
  "register_post_type_args",
  function ($args, $post_type) {
    if (
      $post_type === "post" &&
      get_field("disable_default_blog_post_type", "option") === "0"
    ) {
      $args["labels"] = [
        "name" => _x("News", "Post type general name", "municipio-extended"),
        "singular_name" => _x(
          "News",
          "Post type singular name",
          "municipio-extended",
        ),
        "menu_name" => _x("News", "Admin Menu text", "municipio-extended"),
        "name_admin_bar" => _x(
          "News",
          "Add New on Toolbar",
          "municipio-extended",
        ),
        "add_new" => __("Add news", "municipio-extended"),
        "add_new_item" => __("Add news", "municipio-extended"),
        "new_item" => __("New item", "municipio-extended"),
        "edit_item" => __("Edit news", "municipio-extended"),
        "view_item" => __("View news", "municipio-extended"),
        "all_items" => __("All news", "municipio-extended"),
        "search_items" => __("Search news", "municipio-extended"),
        "parent_item_colon" => __("Parent news:", "municipio-extended"),
        "not_found" => __("No news found.", "municipio-extended"),
        "not_found_in_trash" => __(
          "No news found in Trash.",
          "municipio-extended",
        ),
        "archives" => _x(
          "News archives",
          "The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4",
          "municipio-extended",
        ),
        "insert_into_item" => _x(
          "Insert into news",
          "Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4",
          "municipio-extended",
        ),
        "uploaded_to_this_item" => _x(
          "Uploaded to this news",
          "Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4",
          "municipio-extended",
        ),
        "filter_items_list" => _x(
          "Filter news list",
          "Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”. Added in 4.4",
          "municipio-extended",
        ),
        "items_list_navigation" => _x(
          "News list navigation",
          "Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”. Added in 4.4",
          "municipio-extended",
        ),
        "items_list" => _x(
          "News list",
          "Screen reader text for the items list heading on the post type listing screen. Default “Posts list”. Added in 4.4",
          "municipio-extended",
        ),
      ];
    }

    return $args;
  },
  10,
  2,
);

add_action("wp", function () {
  if (!is_singular()) {
    return; // Ensure this only runs on single posts or pages
  }

  $post_id = get_the_ID();
  $post_type = get_post_type($post_id);

  if (!$post_type) {
    return;
  }

  // Retrieve placement option for the current post type
  $section_id = "municipio_customizer_panel_content_types_" . $post_type;
  $placement =
    \Kirki::get_option(
      \Municipio\Customizer::KIRKI_CONFIG,
      $section_id . "_taxonomy_placement",
    ) ?? "under_header"; // Default to 'under_header' if not set

  // Determine which hook to use
  $hook =
    $placement === "under_header"
      ? "article_content_before"
      : "article_content_after";

  // Add the callback to the selected hook
  add_action($hook, function () use ($post_id, $post_type, $section_id) {
    // Retrieve selected taxonomies for this post type
    $selected_taxonomies = \Kirki::get_option(
      \Municipio\Customizer::KIRKI_CONFIG,
      $section_id . "_taxonomies",
    );

    // Get assigned taxonomies and terms for the post
    $taxonomy_data = mx_get_post_taxonomies_with_terms($post_id);

    // Filter taxonomies based on the selected ones
    $filtered_taxonomies = array_filter(
      $taxonomy_data,
      function ($taxonomy_name) use ($selected_taxonomies) {
        return in_array($taxonomy_name, (array) $selected_taxonomies, true);
      },
      ARRAY_FILTER_USE_KEY,
    );

    // Build the tags array to pass to the view
    $tags = [];
    foreach ($filtered_taxonomies as $taxonomy_info) {
      foreach ($taxonomy_info["terms"] as $term) {
        $color = get_term_meta($term->term_id, "colour", true);
        $redirect_to_data = get_term_meta($term->term_id, "redirect_to", true);

        $redirect_to_url = is_array($redirect_to_data)
          ? $redirect_to_data["url"] ?? null
          : null;

        $tags[] = [
          "label" => $term->name,
          "color" => $color ? $color : null,
          "href" => $redirect_to_url,
        ];
      }
    }

    // Render the taxonomy-tags view with the filtered tags
    echo '<div class="tailwind">';
    echo mx_render_view("mxui.taglist", ["tags" => $tags]);
    echo "</div>";
  });
});

/**
 * Get assigned taxonomies and terms for a specific post.
 *
 * @param int $post_id The ID of the post.
 * @return array An array of taxonomies with terms assigned to the post.
 */
function mx_get_post_taxonomies_with_terms($post_id) {
  // Validate the post ID
  if (!$post_id || !is_numeric($post_id)) {
    return [];
  }

  // Get the post type of the post
  $post_type = get_post_type($post_id);
  if (!$post_type) {
    return [];
  }

  // Get all taxonomies associated with this post type
  $taxonomies = get_object_taxonomies($post_type, "objects");
  $assigned_taxonomies = [];

  foreach ($taxonomies as $taxonomy) {
    // Check if the post has terms in this taxonomy
    $terms = wp_get_post_terms($post_id, $taxonomy->name);

    if (!is_wp_error($terms) && !empty($terms)) {
      // Add to the assigned taxonomies array if terms are found
      $assigned_taxonomies[$taxonomy->name] = [
        "label" => $taxonomy->label,
        "terms" => $terms,
      ];
    }
  }

  return $assigned_taxonomies;
}

add_action("acf/init", function () {
  if (!function_exists("acf_add_local_field")) {
    return;
  }

  acf_add_local_field([
    "key" => "field_redirect_to",
    "label" => _x("Redirect to", "municipio-extended"),
    "name" => "redirect_to",
    "type" => "link",
    "instructions" => __(
      "Select a page or post to redirect to.",
      "municipio-extended",
    ),
    "required" => 0,
    "conditional_logic" => 0,
    "parent" => "group_63e6002cc129c",
    "wrapper" => [
      "width" => "",
      "class" => "",
      "id" => "",
    ],
    "return_format" => "array",
  ]);
});
