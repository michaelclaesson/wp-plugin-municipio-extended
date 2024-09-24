<?php

/**
 * Adds a "Hide title" checkbox to the page edit screen
 */
add_action("edit_form_before_permalink", function () {
  global $post;

  // Only add checkbox for 'page' post type
  if ($post->post_type != "page") {
    return;
  }

  $current = get_post_meta($post->ID, "modularity-module-hide-title", true);
  $current = !empty($current);

  $checked = checked(true, $current, false);

  echo '<div style="margin-top: 20px; margin-bottom: 20px;">';
  wp_nonce_field("modularity_hide_title", "modularity_hide_title_nonce");
  echo '<label style="cursor:pointer;">
    <input type="checkbox" name="modularity-module-hide-title" value="1" ' .
    $checked .
    ">" .
    __("Hide title", "modularity") .
    "</label></div>";
});
add_action(
  "save_post",
  function ($postId, $post) {
    // Only save checkbox for 'page' post type
    if ($post->post_type != "page") {
      return;
    }

    // Verify nonce for security
    if (
      !isset($_POST["modularity_hide_title_nonce"]) ||
      !wp_verify_nonce(
        $_POST["modularity_hide_title_nonce"],
        "modularity_hide_title",
      )
    ) {
      return;
    }

    // Check if the current user has permission to edit the post
    if (!current_user_can("edit_post", $postId)) {
      return;
    }

    // Update the meta field in the database
    if (isset($_POST["modularity-module-hide-title"])) {
      update_post_meta($postId, "modularity-module-hide-title", "1");
    } else {
      update_post_meta($postId, "modularity-module-hide-title", "0");
    }
  },
  10,
  2,
);

/**
 * Hides the page title if the "Hide title" checkbox is checked
 */
add_filter("Municipio/Helper/Post/postObject", function ($postObject) {
  if (is_page($postObject->ID)) {
    if (
      boolval(get_post_meta(get_the_ID(), "modularity-module-hide-title", true))
    ) {
      $postObject->post_title_filtered = "";
    }
  }
  return $postObject;
});
