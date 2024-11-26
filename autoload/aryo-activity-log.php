<?php

/**
 * Skips logging of event_groups changes
 */
add_filter(
  "aal_skip_insert_log",
  function ($skip, $args) {
    switch ($args["object_type"]) {
      case "Taxonomy":
      case "Taxonomies":
        $taxonomy = get_taxonomy($args["object_subtype"]);
        if ($taxonomy->exclude_from_logs ?? false) {
          $skip = true;
        }
        break;
      case "Post":
      case "Posts":
        $post_type = get_post_type($args["object_subtype"]);
        if ($post_type->exclude_from_logs ?? false) {
          $skip = true;
        }
        break;
    }
    return $skip;
  },
  10,
  2,
);
