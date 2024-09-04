<?php

use Kirki\Util\Helper;

/**
 * Kirki doesn't merge default values with the saved values if they are arrays.
 * This fixes that.
 */
add_filter(
  "kirki_get_value",
  function ($value, $setting_name, $default, $option_type) {
    if (is_array($value) && is_array($default)) {
      $value = Helper::array_replace_recursive($default, $value);
    }
    return $value;
  },
  10,
  4,
);
