<?php

/**
 * Adds an MX Debug page under the Tools menu in the WordPress admin area.
 * This page displays the output of mx_get_uploaded_fonts() for debugging purposes.
 */

// Add the MX Debug page to the Tools menu
add_action("admin_menu", function () {
  add_management_page(
    "MX Debug", // Page title
    "MX Debug", // Menu title
    "manage_options", // Capability required
    "mx-debug", // Menu slug
    "mx_debug_page", // Function to display the page
  );
});

/**
 * Renders the MX Debug page content
 */
function mx_debug_page() {
  // Check user capabilities
  if (!current_user_can("manage_options")) {
    return;
  }

  // Get the uploaded fonts
  $fonts = mx_get_uploaded_fonts();
  // Start output
  ?>
  <pre><?php echo json_encode(
    $fonts,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
  ); ?></pre>
    <?php
}
