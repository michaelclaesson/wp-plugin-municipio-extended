<?php

function mx_get_model($class, ...$args) {
  return \MunicipioExtended\Model\Model::create($class, ...$args);
}

function mx_get_post($post_id = null, ...$args) {
  if (!$post_id) {
    $post_id = get_the_ID();
  }
  if (!$post_id) {
    return null;
  }
  return mx_get_model("WpPost", $post_id, ...$args);
}

function mx_get_menu_item($post_id = null, ...$args) {
  if (!$post_id) {
    $post_id = get_the_ID();
  }
  if (!$post_id) {
    return null;
  }
  return mx_get_model("WpMenuItem", $post_id, ...$args);
}

function mx_get_image($attachment_id, $size = "thumbnail") {
  if (!$attachment_id) {
    return null;
  }
  return mx_get_model("WpImage", $attachment_id, $size);
}

function mx_get_icon($input, ...$args) {
  if (!$input) {
    return null;
  }
  return mx_get_model("Icon", $input, ...$args);
}

function mx_date($value, ...$args) {
  if (!$value) {
    return null;
  }
  if (is_string($value)) {
    $value = strtotime($value);
  }
  return mx_get_model("Date", $value, ...$args);
}
