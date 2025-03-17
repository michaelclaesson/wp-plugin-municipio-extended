<?php

add_action("init", function () {
  register_post_type("async_job", [
    "labels" => [
      "name" => __("Async jobs", "municipio-extended"),
      "singular_name" => __("Async job", "municipio-extended"),
      "add_new" => __("Add New", "municipio-extended"),
      "add_new_item" => __("Add New Job", "municipio-extended"),
      "edit_item" => __("Edit Job", "municipio-extended"),
      "new_item" => __("New Job", "municipio-extended"),
      "view_item" => __("View Job", "municipio-extended"),
      "not_found" => __("No jobs found", "municipio-extended"),
      "not_found_in_trash" => __(
        "No jobs found in trash",
        "municipio-extended",
      ),
      "search_items" => __("Search jobs", "municipio-extended"),
      "menu_name" => __("Async Jobs", "municipio-extended"),
      "all_items" => __("Async Jobs", "municipio-extended"),
      "archives" => __("Async Jobs", "municipio-extended"),
    ],
    "hierarchical" => true,
    "public" => false,
    "show_ui" => true,
    "supports" => ["title"],
    "show_in_menu" => "tools.php",
  ]);
});

add_action("acf/init", function () {
  global $mx_async_job_handlers;
  $handler_choices = array_combine(
    array_keys($mx_async_job_handlers),
    array_keys($mx_async_job_handlers),
    // array_map(function ($handler) {
    //   return $handler["label"];
    // }, $mx_async_job_handlers),
  );
  acf_add_local_field_group([
    "key" => "group_mx_async_job",
    "title" => "Async Job",
    "fields" => [
      [
        "key" => "field_mx_async_job_status",
        "label" => "Status",
        "name" => "mx_async_job_status",
        "type" => "select",
        "choices" => [
          "pending" => __("Pending", "municipio-extended"),
          "running" => __("Running", "municipio-extended"),
          "completed" => __("Completed", "municipio-extended"),
          "failed" => __("Failed", "municipio-extended"),
          // "aborted" => __("Aborted","municipio-extended"),
        ],
        "default_value" => "pending",
      ],
      [
        "key" => "field_mx_async_job_handler",
        "label" => "Handler",
        "name" => "mx_async_job_handler",
        "type" => "select",
        "choices" => $handler_choices,
      ],
      [
        "key" => "field_mx_async_job_payload",
        "label" => "Payload",
        "name" => "mx_async_job_payload",
        "type" => "acfe_code_editor",
        "indent_unit" => 2,
        "mode" => "application/x-json",
      ],
      [
        "key" => "field_mx_async_job_max_runs",
        "label" => "Maximum runs",
        "name" => "mx_async_max_runs",
        "type" => "number",
        "default_value" => 3,
        "min" => 1,
        "step" => 1,
      ],
    ],
    "location" => [
      [
        [
          "param" => "post_type",
          "operator" => "==",
          "value" => "async_job",
        ],
      ],
    ],
  ]);
});

/*
- Register trigger that can be called from cron
- Register trigger that can be called from WP CLI
- Register trigger that can be called from admin ajax
- Register trigger that can be called from admin UI
*/

function mx_async_is_running() {
  // Make a query to the wp_postmeta table to check if there is a running job as efficiently as possible
  global $wpdb;
  $query = $wpdb->prepare(
    "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",
    "mx_async_job_status",
    "running",
  );
  $running_job = $wpdb->get_var($query);
  return !!$running_job;
}

function mx_get_pending_async_jobs_count() {
  // Use $wbdb to get the count of pending jobs
  global $wpdb;
  $query = $wpdb->prepare(
    "SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",
    "mx_async_job_status",
    "pending",
  );
  return (int) $wpdb->get_var($query);
}

function mx_async_trigger() {
  if (mx_async_is_running()) {
    // A job is already running
    return;
  }
  // Get the next pending job, ordered by menu_order and ID
  $jobs = get_posts([
    "post_type" => "async_job",
    "posts_per_page" => 1,
    "post_status" => "publish",
    "orderby" => "menu_order ID",
    "order" => "ASC",
    "meta_query" => [
      [
        "key" => "mx_async_job_status",
        "value" => "pending",
      ],
    ],
  ]);
  // error_log(var_export(["jobs" => $jobs], true));
  if (!$jobs) {
    // No pending jobs found
    return;
  }
  $job = $jobs[0];
  $query_args = [
    "action" => "mx_handle_async_job",
    "nonce" => wp_create_nonce("mx_handle_async_job"),
  ];
  $url = add_query_arg($query_args, admin_url("admin-ajax.php"));
  $data = [
    "job_id" => $job->ID,
  ];
  $args = [
    "timeout" => 5,
    "blocking" => false,
    "body" => $data,
    "cookies" => $_COOKIE, // Passing cookies ensures request is performed as initiating user.
    "sslverify" => apply_filters("https_local_ssl_verify", false), // Local requests, fine to pass false.
  ];
  // error_log(var_export(["wp_remote_post", esc_url_raw($url), $args], true));
  return wp_remote_post(esc_url_raw($url), $args);
}

add_action("wp_ajax_mx_async_trigger", function () {
  mx_async_trigger();
  wp_send_json(["success" => true]);
});

function wp_ajax_mx_handle_async_job_cb() {
  $job_id = $_POST["job_id"];
  // error_log(var_export(["POST" => $_POST], true));
  session_write_close();
  check_ajax_referer("mx_handle_async_job", "nonce");
  mx_handle_async_job($job_id);
  wp_send_json(["success" => true]);
}

add_action("wp_ajax_mx_handle_async_job", "wp_ajax_mx_handle_async_job_cb");
add_action(
  "wp_ajax_nopriv_mx_handle_async_job",
  "wp_ajax_mx_handle_async_job_cb",
);

add_filter("cron_schedules", function ($schedules) {
  $schedules["5_minutes"] = [
    "interval" => 300,
    "display" => __("Every five minutes", "municipio-extended"),
  ];
  return $schedules;
});
add_action("init", function () {
  if (!wp_next_scheduled("mx_async_trigger_event")) {
    wp_schedule_event(time(), "5_minutes", "mx_async_trigger_event");
  }
});
add_action("mx_async_trigger_event", "mx_async_trigger");

function mx_add_async_job($handler, $payload) {
  // TODO: Insert post
  $job_id = wp_insert_post([
    "post_type" => "async_job",
    "post_title" => $handler,
    "post_status" => "publish",
  ]);
  // error_log(
  //   var_export(["mx_add_async_job", $handler, $payload, $job_id], true),
  // );
  update_field("mx_async_job_handler", $handler, $job_id);
  update_field("mx_async_job_status", "pending", $job_id);
  update_field(
    "mx_async_job_payload",
    json_encode($payload, JSON_PRETTY_PRINT),
    $job_id,
  );
  mx_async_trigger();
}

// function mx_lock_async_job($job_id) {
// }

// function mx_unlock_async_job($job_id) {
// }

// function mx_postpone_async_job($job_id) {
// }

global $mx_async_job_handlers;
$mx_async_job_handlers = [];

function mx_register_async_job_handler($handler, $args) {
  global $mx_async_job_handlers;
  $mx_async_job_handlers[$handler] = $args;
}

function mx_handle_async_job($job_id) {
  global $mx_async_job_handlers;
  $job = get_post($job_id);
  $handler = get_field("mx_async_job_handler", $job);
  $payload = json_decode(get_field("mx_async_job_payload", $job), true);
  update_field("mx_async_job_status", "running", $job);
  $start_time = microtime(true);
  update_post_meta($job->ID, "mx_async_job_start_time", $start_time);
  // error_log(
  //   var_export(["mx_async_job_handlers" => $mx_async_job_handlers], true),
  // );
  try {
    $result = $mx_async_job_handlers[$handler]["callback"]($payload, $job);
    if ($result) {
      if (is_wp_error($result)) {
        throw new \Exception($result->get_error_message());
      }
      update_field(
        "mx_async_job_payload",
        json_encode($result, JSON_PRETTY_PRINT),
        $job,
      );
      $status = "pending";
    } else {
      $status = "completed";
    }
  } catch (\Exception $e) {
    $status = "failed";
  } finally {
    $end_time = microtime(true);
    $run = [
      "start_time" => $start_time,
      "end_time" => $end_time,
      "duration" => $end_time - $start_time,
      "payload" => $payload,
      "status" => $status,
    ];
    add_post_meta($job->ID, "mx_async_job_run", $run);
    error_log(var_export($status, true));
    if ($status != "completed") {
      $max_runs = get_field("mx_async_max_runs", $job) ?: 3;
      error_log(var_export($max_runs, true));
      $runs = get_post_meta($job->ID, "mx_async_job_run");
      error_log(var_export(count($runs), true));
      if (count($runs) >= $max_runs) {
        $status = "failed";
      } else {
        $status = "pending";
      }
    }
    update_field("mx_async_job_status", $status, $job);
  }
  mx_async_trigger();
  return $run;
}

add_action("add_meta_boxes", function () {
  add_meta_box(
    "mx_async_job",
    __("Async Job", "municipio-extended"),
    function ($post) {
      $runs = get_post_meta($post->ID, "mx_async_job_run"); ?>
      <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
          <tr>
            <th>Start time</th>
            <th>End time</th>
            <th>Duration</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($runs as $run): ?>
            <tr>
              <td><?php echo esc_html($run["start_time"]); ?></td>
              <td><?php echo esc_html($run["end_time"]); ?></td>
              <td><?php echo esc_html($run["duration"]); ?></td>
              <td><?php echo esc_html($run["status"]); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php
    },
    "async_job",
    "normal",
    "default",
  );
});

// function mx_async_pause() {
// }

// function mx_async_resume() {
// }

add_action("admin_post_mx_async_trigger", function () {
  check_admin_referer("mx_async_trigger");
  mx_async_trigger();
  error_log(var_export("resumed", true));
  wp_redirect(wp_get_referer());
  exit();
});

add_action("admin_init", function () {
  // We use this hook because it runs at the top of the post list table.
  add_action("admin_notices", function () {
    global $typenow;
    $is_running = mx_async_is_running();
    $pending_jobs_count = mx_get_pending_async_jobs_count();
    if ($typenow === "async_job"): ?>
      <div class="notice notice-info" style="margin-bottom: 20px; padding: 10px; border-left: 4px solid #0073aa;">
        <p>
          <strong>Status:</strong>
          <?php echo $is_running ? "Running." : "Idle."; ?>
          <?php echo $pending_jobs_count . " pending jobs."; ?>
        </p>
        <?php if (!$is_running && $pending_jobs_count): ?>
          <form action="admin-post.php" method="post">
            <input type="hidden" name="action" value="mx_async_trigger">
            <?php wp_nonce_field("mx_async_trigger"); ?>
            <button class="button button-primary">Resume</button>
          </form>
        <?php endif; ?>
      </div>
      <?php endif;
  });
});

add_filter("manage_edit-async_job_columns", function ($columns) {
  // Insert a new column after the title column
  $new_columns = [];
  foreach ($columns as $key => $value) {
    if ($key === "broken-links") {
      continue;
    }
    if ($key === "date") {
      continue;
    }
    $new_columns[$key] = $value;
    if ($key === "title") {
      // Job status
      $new_columns["mx-async-job-status"] = __("Status", "municipio-extended");
      // Number of runs
      $new_columns["mx-async-job-runs"] = __(
        "Number of runs",
        "municipio-extended",
      );
      // Last run date
      $new_columns["mx-async-job-last-run"] = __(
        "Last run",
        "municipio-extended",
      );
    }
  }
  return $new_columns;
});

add_action(
  "manage_async_job_posts_custom_column",
  function ($column, $post_id) {
    switch ($column) {
      case "mx-async-job-status":
        $job_status = get_post_meta($post_id, "mx_async_job_status", true);
        echo !empty($job_status) ? esc_html($job_status) : "";
        break;
      case "mx-async-job-runs":
        $runs = get_post_meta($post_id, "mx_async_job_run");
        echo count($runs);
        break;
      case "mx-async-job-last-run":
        $runs = get_post_meta($post_id, "mx_async_job_run");
        $last_run = end($runs);
        echo !empty($last_run)
          ? esc_html(date("Y-m-d H:i:s", round($last_run["end_time"])))
          : "";
        break;
    }
  },
  10,
  2,
);

global $mx_async_view_edit_job_statuses;
$mx_async_view_edit_job_statuses = [
  "" => [
    "label" => __("Incomplete Jobs", "municipio-extended"),
    "meta_value" => ["completed"],
    "compare" => "NOT IN",
  ],
  "completed" => [
    "label" => __("Completed Jobs", "municipio-extended"),
    "meta_value" => ["completed"],
    "compare" => "IN",
  ],
  "all" => ["label" => __("All Jobs", "municipio-extended")],
];

add_filter("views_edit-async_job", function ($views) {
  global $post_type, $wpdb, $mx_async_view_edit_job_statuses;
  if ($post_type !== "async_job") {
    return $views;
  }
  $base_url = admin_url("edit.php?post_type=async_job");
  $current_status = $_GET["job_status"] ?? "";
  $views = [];
  foreach ($mx_async_view_edit_job_statuses as $key => $status) {
    $label = $status["label"];
    $meta_value = $status["meta_value"] ?? "";
    $compare = $status["compare"] ?? (is_array($meta_value) ? "IN" : "=");
    $count_query = "SELECT COUNT(*) FROM {$wpdb->postmeta} pm
                      INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                      WHERE p.post_type = 'async_job' AND pm.meta_key = 'mx_async_job_status'";
    if (!empty($meta_value)) {
      $count_query .= sprintf(
        " AND pm.meta_value %s %s",
        $compare,
        "('" . implode("','", (array) $meta_value) . "')",
      );
    }
    $count = $wpdb->get_var($count_query) ?: 0;
    $class = $current_status === $key ? "current" : "";
    $views[$key ?: "incomplete"] = sprintf(
      '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
      esc_url(add_query_arg("job_status", $key, $base_url)),
      esc_attr($class),
      esc_html($label),
      intval($count),
    );
  }
  return $views;
});

add_action("pre_get_posts", function ($query) {
  global $pagenow, $post_type, $mx_async_view_edit_job_statuses;
  if ($pagenow !== "edit.php" || $post_type !== "async_job" || !is_admin()) {
    return;
  }
  $key = $_GET["job_status"] ?? "";
  if ($key === "all") {
    return;
  }
  $status = $mx_async_view_edit_job_statuses[$key];
  $query->query_vars["meta_query"] = [
    [
      "key" => "mx_async_job_status",
      "value" => $status["meta_value"],
      "compare" =>
        $status["compare"] ?? (is_array($status["meta_value"]) ? "IN" : "="),
    ],
  ];
});

/*
DEBUGGING
*/
add_action("plugins_loaded", function () {
  mx_register_async_job_handler("mx_test", [
    "callback" => function ($payload) {
      sleep($payload["sleep"] ?? 0);
      switch ($payload["next_status"] ?? "completed") {
        case "pending":
          return $payload;
        case "failed":
          return new \WP_Error("mx_test_error", "Test error");
      }
    },
  ]);
});
