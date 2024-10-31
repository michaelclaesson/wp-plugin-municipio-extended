<?php

// Remove field for link text
add_action(
  "admin_init",
  function () {
    if (function_exists("acf_remove_local_field")) {
      acf_remove_local_field("field_65002bce6d459");
    }
  },
  20,
);

// add_filter("Modularity/Module/ManualInput/ManualInput", function ($data) {
//   var_dump($data);
//   // $data["link"] = get_permalink();
//   // return $data;
// });
