<?php

/**
 * Change posttype of all mod-files1 to mod-fileslist
 */
$posts = get_posts([
  "post_type" => "mod-files1",
  "posts_per_page" => -1,
]);

$total = count($posts);
$count = 0;

// Enables mod-fileslist if it's not enabled
$modularity_options = get_option("modularity_options");
if (
  $modularity_options["enabled-modules"] ??
  null &&
    is_array($modularity_options["enabled-modules"]) &&
    !in_array("mod-fileslist", $modularity_options["enabled-modules"])
) {
  $modularity_options["enabled-modules"][] = "mod-fileslist";
  update_option("modularity_options", $modularity_options);
}

foreach ($posts as $post) {
  mx_migration_breakpoint(function () use ($count, $total) {
    mx_migration_progress_log(
      "$count of $total modules changed from mod-files1 to mod-fileslist",
    );
  });
  $old_field_value = get_field("files", $post->ID);
  $new_field_value = array_map(
    function ($file) {
      return [
        "file" => is_array($file) ? $file["id"] : $file,
      ];
    },
    $old_field_value ?: [],
  );
  delete_field("files", $post->ID);
  wp_update_post([
    "ID" => $post->ID,
    "post_type" => "mod-fileslist",
  ]);
  update_field("file_list", $new_field_value, $post->ID);
  update_field("show_filter", false, $post->ID);
  $count++;
}

// Disables mod-files1 if it's enabled
$modularity_options = get_option("modularity_options");
if (
  $modularity_options["enabled-modules"] ??
  null &&
    is_array($modularity_options["enabled-modules"]) &&
    !in_array("mod-files1", $modularity_options["enabled-modules"])
) {
  $modularity_options["enabled-modules"] = array_diff(
    $modularity_options["enabled-modules"],
    ["mod-files1"],
  );
  update_option("modularity_options", $modularity_options);
}
