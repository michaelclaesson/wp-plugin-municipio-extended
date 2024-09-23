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

foreach ($posts as $post) {
  mx_migration_breakpoint(function () use ($count, $total) {
    mx_migration_progress_log(
      "$count of $total modules changed from mod-files1 to mod-fileslist",
    );
  });
  $fields = get_fields($posts[0]->ID);
  $old_field_value = get_field("files", $post->ID);
  $new_field_value = array_map(
    function ($file) {
      return [
        "file" => $file["id"],
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
