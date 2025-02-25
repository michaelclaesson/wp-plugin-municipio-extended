<?php

// add_filter("Modularity/Module/ManualInput/DefaultValues", function ($values) {
//   $values["link_text"] = "";
//   return $values;
// });

add_filter("Modularity/Display/mod-manualinput/viewData", function ($data) {
  if (!empty($data["manualInputs"]) && is_array($data["manualInputs"])) {
    foreach ($data["manualInputs"] as $index => $input) {
      $metaKey = "manual_inputs_{$index}_content";
      if (!empty($data["meta"][$metaKey][0])) {
        $data["manualInputs"][$index]["content"] = mx_process_content(
          $data["meta"][$metaKey][0],
          ["wpautop" => true],
        );
      }
    }
  }
  return $data;
});
