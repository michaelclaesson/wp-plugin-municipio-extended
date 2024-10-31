<?php

add_filter("Modularity/Module/ManualInput/DefaultValues", function ($values) {
  $values["link_text"] = "";
  return $values;
});

// Remove text field for segment
add_action(
  "admin_init",
  function () {
    if (function_exists("acf_remove_local_field")) {
      acf_remove_local_field("field_65002bce6d459");
    }
  },
  20,
);
