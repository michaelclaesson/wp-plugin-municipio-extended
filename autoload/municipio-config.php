<?php

add_filter("municipio/remove_script_versions", "__return_false");

add_filter("Municipio/Template/viewData", function ($data) {
  $data["mainContentBottomMargin"] = 0;
  return $data;
});

/**
 * Remove the instant.page script added by the Municipio theme.
 */
add_action(
  "wp_enqueue_scripts",
  function () {
    wp_dequeue_script("instantpage-js");
  },
  6, // Right after the script is enqueued
);
