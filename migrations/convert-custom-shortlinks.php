<?php

use Red_Item;

$posts = get_posts([
  "post_type" => "custom-short-link",
  "posts_per_page" => -1,
]);

$total = count($posts);
$count = 0;

if (!is_plugin_active("redirection/redirection.php")) {
  $error = activate_plugin("redirection/redirection.php");
  if (is_wp_error($error)) {
    throw new Exception("Failed to activate the Redirection plugin");
    return;
  }
}

/**
 * Create a new Redirection item for each custom shortlink
 */
foreach ($posts as $post) {
  mx_migration_breakpoint(function () use ($count, $total) {
    mx_migration_progress_log(
      "$count of $total custom shortlinks migrated to the Redirection plugin",
    );
  });
  $fields = get_fields($post->ID);

  $type = $fields["custom_short_links_redirect_url_type"] ?? null;
  if (!$type) {
    continue;
  }

  switch ($type) {
    case "internal":
      $target = $fields["custom_short_links_redirect_to_internal"] ?? null;
      $target = str_replace(get_home_url(), "", $target);
      break;
    case "external":
      $target = $fields["custom_short_links_redirect_to_external"] ?? null;
      break;
  }

  $source = "/" . $post->post_title;

  if (!$target) {
    continue;
  }

  $group_id = 1;

  $action_code = $fields["custom_short_links_redirect_method"] ?? null;
  if (empty($action_code) || !is_numeric($action_code)) {
    $action_code = 301;
  } else {
    $action_code = (int) $action_code;
  }

  $item = [
    "url" => $source,
    "action_data" => ["url" => $target],
    "regex" => false,
    "group_id" => $group_id,
    "match_type" => "url",
    "action_type" => "url",
    "action_code" => $action_code,
  ];

  Red_Item::create($item);

  wp_delete_post($post->ID, false); // Trash the post

  $count++;
}

mx_migration_progress_log(
  "$count of $total custom shortlinks migrated to the Redirection plugin",
);

// Disable the Custom Shortlinks plugin when we're done
deactivate_plugins("custom-short-links/custom-short-links.php");
