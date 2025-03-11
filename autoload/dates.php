<?php

/**
 * Removes HTML from excerpts
 */
add_filter("Municipio/Helper/Post/postObject", function ($postObject) {
  if (!empty($postObject->post_date)) {
    if (!empty($postObject->post_time_formatted)) {
      $postObject->post_time_formatted = mx_date(
        $postObject->post_date,
        "time",
      );
    }
    if (!empty($postObject->post_date_time_formatted)) {
      $postObject->post_date_time_formatted = mx_date(
        $postObject->post_date,
        "dateTime",
      );
    }
    if (!empty($postObject->post_date_formatted)) {
      $postObject->post_date_formatted = mx_date(
        $postObject->post_date,
        "date",
      );
    }
  }
  return $postObject;
});
