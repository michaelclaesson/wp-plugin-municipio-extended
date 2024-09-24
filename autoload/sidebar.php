<?php

/**
 * This file does some tricks to allow us to wrap the sidebar in a blade template.
 */

define("MUNICIPIO_EXTENDED_SLOT_PLACEHOLDER", '$$_SLOT_$$');

add_action(
  "dynamic_sidebar_before",
  function ($index, $has_widgets) {
    if (!$has_widgets) {
      return;
    }
    echo explode(
      MUNICIPIO_EXTENDED_SLOT_PLACEHOLDER,
      mx_render_view("sidebar-inner-wrapper", [
        "index" => $index,
        "hasWidgets" => $has_widgets,
        "slot" => MUNICIPIO_EXTENDED_SLOT_PLACEHOLDER,
      ]),
    )[0];
  },
  10,
  2,
);

add_action(
  "dynamic_sidebar_after",
  function ($index, $has_widgets) {
    if (!$has_widgets) {
      return;
    }
    echo explode(
      MUNICIPIO_EXTENDED_SLOT_PLACEHOLDER,
      mx_render_view("sidebar-inner-wrapper", [
        "index" => $index,
        "hasWidgets" => $has_widgets,
        "slot" => MUNICIPIO_EXTENDED_SLOT_PLACEHOLDER,
      ]),
    )[1];
  },
  20, // Modules are rendered by this hook so we need to run after them
  2,
);
