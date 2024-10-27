<?php

/**
 * Change posttype of all mod-fact to mod-text
 */
$posts = get_posts([
  "post_type" => "mod-fact",
  "posts_per_page" => -1,
]);

$total = count($posts);
$count = 0;

// Enables mod-text if it's not enabled
$modularity_options = get_option("modularity_options");
if (
  $modularity_options["enabled-modules"] ??
  null &&
    is_array($modularity_options["enabled-modules"]) &&
    !in_array("mod-text", $modularity_options["enabled-modules"])
) {
  $modularity_options["enabled-modules"][] = "mod-text";
  update_option("modularity_options", $modularity_options);
}

foreach ($posts as $post) {
  mx_migration_breakpoint(function () use ($count, $total) {
    mx_migration_progress_log(
      "$count of $total modules changed from mod-fact to mod-text",
    );
  });
  wp_update_post([
    "ID" => $post->ID,
    "post_type" => "mod-text",
  ]);
  update_post_meta($post->ID, "mx_was_mod_fact", true);
  $count++;
}

mx_migration_progress_log(
  "$count of $total modules changed from mod-fact to mod-text",
);

// Disables mod-fact if it's enabled
$modularity_options = get_option("modularity_options");
if (
  $modularity_options["enabled-modules"] ??
  null &&
    is_array($modularity_options["enabled-modules"]) &&
    !in_array("modularity-fact", $modularity_options["enabled-modules"])
) {
  $modularity_options["enabled-modules"] = array_diff(
    $modularity_options["enabled-modules"],
    ["modularity-fact"],
  );
  update_option("modularity_options", $modularity_options);
}

// Disable the Modularity Fact plugin when we're done
deactivate_plugins("modularity-fact/modularity-fact.php");
