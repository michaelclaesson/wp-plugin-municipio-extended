<?php
add_action(
  "admin_menu",
  function () {
    remove_submenu_page("themes.php", "acf-options-css");
  },
  999,
);

add_action(
  "customize_register",
  function ($wp_customize) {
    $wp_customize->remove_section("custom_css");
  },
  20,
);

add_filter("Municipio/allowCustomCode", function ($allow) {
  if (
    defined("MX_ALLOW_CUSTOM_CODE") &&
    constant("MX_ALLOW_CUSTOM_CODE") === true
  ) {
    return $allow;
  }
  return false;
});
