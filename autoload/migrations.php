<?php

class MxMigrationTimeoutException extends Exception {
}

/**
 * Get all migrations
 * @return array Migrations
 */
function mx_get_migrations() {
  $files = glob(__DIR__ . "/../migrations/*.php");
  $migrations = array_map(function ($file) {
    $name = basename($file, ".php");
    return [
      "name" => $name,
      "file" => $file,
      "status" => get_option("mx_migration_status_" . $name),
    ];
  }, $files);
  return $migrations;
}

/**
 * Log a migration action. Integrates with the Activity Log plugin and PHP error
 * log.
 * @param string $action Action
 * @param string $message Message
 */
function mx_migration_log($action, $message) {
  global $mx_current_migration;
  $object_subtype = $mx_current_migration;
  error_log(
    "Migration $mx_current_migration $action" . ($message ? ": $message" : ""),
  );
  if (function_exists("aal_insert_log")) {
    aal_insert_log([
      "action" => $action,
      "object_type" => "Migrations",
      "object_subtype" => $object_subtype,
      "object_name" => $mx_current_migration,
      // "object_id" => 0,
    ]);
  }
}

/**
 * Adds Migrations to the list of object types in the Activity Log plugin.
 */
add_filter("aal_notification_get_object_types", function ($object_types) {
  $object_types[] = "Migrations";
  return $object_types;
});

/**
 * Allows users with the manage_options role to view Migrations on the Activity
 * Log page.
 */
add_filter("aal_init_roles", function ($roles) {
  $roles["manage_options"][] = "Migrations";
  return $roles;
});

/**
 * Log a progress message for the current migration.
 * @param string $message Message
 * @return void
 */
function mx_migration_progress_log($message = "") {
  mx_migration_log("progressed", $message);
}

/**
 * Log an error message for the current migration.
 * @param string $message Message
 * @return void
 */
function mx_migration_error_log($message = "") {
  mx_migration_log("errored", $message);
}

/**
 * Log a finish message for the current migration.
 * @param string $message Message
 * @return void
 */
function mx_migration_finish_log($message = "") {
  mx_migration_log("finished", $message);
}

/**
 * Log a halt message for the current migration.
 * @param string $message Message
 * @return void
 */
function mx_migration_halt_log($message = "") {
  mx_migration_log("halted", $message);
}

/**
 * Runs pending migrations on admin init.
 */
add_action(
  "admin_init",
  function () {
    global $mx_current_migration;
    $migrations = mx_get_migrations();
    global $mx_migration_start_time;
    $mx_migration_start_time = microtime(true);
    foreach ($migrations as $migration) {
      $name = $migration["name"];
      $file = $migration["file"];
      $status = get_option("mx_migration_status_" . $name) ?: "pending";
      if ($status === "pending") {
        error_log('Running migration "' . $name . '"...');
        update_option("mx_migration_status_" . $name, "running");
        $mx_current_migration = $name;
        try {
          require_once $file;
          mx_migration_finish_log();
          update_option("mx_migration_status_" . $name, "done");
        } catch (MxMigrationTimeoutException $e) {
          mx_migration_halt_log($e->getMessage());
          update_option("mx_migration_status_" . $name, "pending");
        } catch (Exception $e) {
          mx_migration_error_log($e->getMessage());
          update_option("mx_migration_status_" . $name, "error");
        } finally {
          $mx_current_migration = null;
        }
      }
    }
  },
  99,
);

/**
 * Get seconds since migrations started for this request.
 * @return float Duration
 */
function mx_migration_duration() {
  global $mx_migration_start_time;
  return microtime(true) - $mx_migration_start_time;
}

/**
 * Use this function to add a breakpoint to a migration to prevent timeouts.
 * @param callable $callback Callback
 */
function mx_migration_breakpoint($callback) {
  if (mx_migration_duration() > 10) {
    $callback();
    throw new MxMigrationTimeoutException("Migration paused");
  }
}

/**
 * Add a menu item for Migrations in the admin menu.
 */
add_action("admin_menu", function () {
  add_submenu_page(
    "tools.php",
    _x("Migrations", "Admin Page Title", "municipio-extended"),
    _x("Migrations", "Admin Menu Item", "municipio-extended"),
    "manage_options",
    "mx-migrations",
    "mx_migrations_page_cb",
  );
});

/**
 * Callback for the Migrations admin page.
 */
function mx_migrations_page_cb() {
  $migrations = mx_get_migrations(); ?>
  <div class="wrap">
    <h1><?php _e("Migrations", "municipio-extended"); ?></h1>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th><?php _e("Name", "municipio-extended"); ?></th>
          <th><?php _e("Status", "municipio-extended"); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($migrations as $migration): ?>
          <tr>
            <td><?php echo $migration["name"]; ?></td>
            <td><?php echo $migration["status"]; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php
}
