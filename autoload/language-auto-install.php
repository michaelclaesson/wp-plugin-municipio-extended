<?php
function check_and_install_language() {
  // Path to the languages directory
  $languages_dir = WP_CONTENT_DIR . "/languages";

  // Check if the languages directory exists, if not create it
  if (!file_exists($languages_dir)) {
    if (!mkdir($languages_dir, 0755, true)) {
      error_log(
        "Failed to create languages directory: " .
          $languages_dir .
          " - " .
          __FILE__ .
          " " .
          __LINE__,
      );
      return;
    }
  }

  // Check if the Swedish language pack is installed
  if (!file_exists($languages_dir . "/sv_SE.mo")) {
    // Detect environment and set PHP and WP-CLI paths accordingly
    $php_path = null;
    $wp_cli_path = null;

    // Check for PHP binary
    if (file_exists("/usr/local/bin/php")) {
      $php_path = "/usr/local/bin/php";
    } elseif (file_exists("/opt/homebrew/bin/php")) {
      $php_path = "/opt/homebrew/bin/php";
    } else {
      error_log("PHP binary not found. Cannot proceed with language installation.");
      return;
    }

    // Check for WP-CLI binary
    if (file_exists("/usr/local/bin/wp")) {
      $wp_cli_path = "/usr/local/bin/wp";
    } elseif (file_exists("/opt/homebrew/bin/wp")) {
      $wp_cli_path = "/opt/homebrew/bin/wp";
    } else {
      error_log("WP-CLI binary not found. Cannot proceed with language installation.");
      return;
    }

    // Run the command to install the language
    exec(
      "$php_path $wp_cli_path language core install sv_SE",
      $output,
      $return_var,
    );
    if ($return_var !== 0) {
      error_log(
        '$return_var: ' . $return_var . " - " . __FILE__ . " " . __LINE__,
      );
      error_log(
        '$output: ' .
          print_r($output, true) .
          " - " .
          __FILE__ .
          " " .
          __LINE__,
      );
    } else {
      // Force a page reload if the language was successfully installed
      if (!headers_sent()) {
        header("Refresh:0");
        exit();
      } else {
        echo '<script type="text/javascript">window.location.reload();</script>';
        exit();
      }
    }
  }
}

// Hook into the admin_init action to check when the admin panel is accessed
add_action("admin_init", "check_and_install_language");
