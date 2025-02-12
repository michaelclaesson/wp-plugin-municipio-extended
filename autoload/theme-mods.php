<?php

function mx_import_theme_mods($mods) {
  foreach ($mods as $key => $value) {
    if (in_array($key, [0, "nav_menu_locations", "sidebars_widgets"])) {
      continue;
    }
    set_theme_mod($key, $value);
  }
}

/**
 * Adds an ajax action to get all theme mods
 */
function mx_ajax_get_theme_mods_handler() {
  $mods = get_theme_mods();
  wp_send_json($mods);
}
add_action("wp_ajax_get_theme_mods", "mx_ajax_get_theme_mods_handler");
add_action("wp_ajax_nopriv_get_theme_mods", "mx_ajax_get_theme_mods_handler");

/**
 * Adds a submenu page to the admin menu where you can import theme mods from
 * another site on the network.
 */
add_action("admin_menu", function () {
  add_submenu_page(
    "tools.php",
    __("Clone Theme Customizations", "municipio-extended"),
    __("Clone Theme Customizations", "municipio-extended"),
    "manage_options",
    "mx-import-theme-mods",
    "mx_render_theme_mods_submenu_page",
  );
});

add_action("admin_notices", "show_admin_notice");

function show_admin_notice() {
  // Ensure we are on the correct admin page
  if (!isset($_GET["page"]) || $_GET["page"] !== "mx-import-theme-mods") {
    return;
  }

  if (!isset($_GET["form_status"])) {
    return;
  }

  $class = $_GET["form_status"] === "success" ? "updated" : "error";
  $message =
    $_GET["form_status"] === "success"
      ? __("Theme customizations cloned successfully.", "municipio-extended")
      : __("Theme customizations could not be cloned.", "municipio-extended");

  echo "<div class='$class notice is-dismissible'><p>$message</p></div>";
}

function mx_render_theme_mods_submenu_page() {
  ?>
  <div class="wrap">
    <h1><?php _e("Clone Theme Customizations", "municipio-extended"); ?></h1>
    <form action="admin-post.php" method="post" class="wp-form">
      <p><?php _e(
        "Select a site to clone theme customizations from.",
        "municipio-extended",
      ); ?></p>
      <input type="hidden" name="action" value="mx_import_theme_mods_action">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row">
              <label for="site_id"><?php _e(
                "Site",
                "municipio-extended",
              ); ?></label>
            </th>
            <td>
              <select name="site_id" id="site_id">
                <?php
                $sites = get_sites();
                foreach ($sites as $site):

                  $site_id = $site->blog_id;
                  $site_name = get_blog_option($site_id, "blogname");
                  ?>
                  <option value="<?php echo $site_id; ?>"><?php echo $site_name; ?></option>
                  <?php
                endforeach;
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="debug"><?php _e(
                "Debug",
                "municipio-extended",
              ); ?></label>
            </th>
            <td>
              <input type="checkbox" name="debug" id="debug">
            </td>
          </tr>
        </tbody>
      </table>
      <?php submit_button(__("Clone", "municipio-extended")); ?>
    </form>
  </div>
  <?php
}

/**
 * Imports theme mods from another site on the network.
 */
add_action("admin_post_mx_import_theme_mods_action", function () {
  $site_id = $_POST["site_id"];
  $site_url = get_site_url($site_id);
  if (defined("DISABLE_LOOPBACK_HTTPS") && constant("DISABLE_LOOPBACK_HTTPS")) {
    $site_url = preg_replace("/^https:/", "http:", $site_url);
  }
  if ($_POST["debug"]) {
    echo "<pre>", var_export($site_url, true), "</pre>";
  }
  $url = $site_url . "/wp-admin/admin-ajax.php?action=get_theme_mods";
  $response = wp_remote_get($url, [
    "sslverify" => false,
  ]);
  if (is_wp_error($response)) {
    if ($_POST["debug"]) {
      echo "<pre>", var_export($response->get_error_messages(), true), "</pre>";
      exit();
    }
    $success = false;
  } else {
    if ($_POST["debug"]) {
      echo "<pre>", var_export($response["body"], true), "</pre>";
    }
    $theme_mods = json_decode($response["body"], true);
    if ($_POST["debug"]) {
      echo "<pre>", var_export($theme_mods, true), "</pre>";
      exit();
    }
  }
  if (empty($theme_mods)) {
    $success = false;
  } else {
    mx_import_theme_mods($theme_mods);
    $success = true;
  }
  $redirect_url = add_query_arg(
    "form_status",
    $success ? "success" : "error",
    admin_url("tools.php?page=mx-import-theme-mods"),
  );
  wp_redirect($redirect_url);
  exit();
});
