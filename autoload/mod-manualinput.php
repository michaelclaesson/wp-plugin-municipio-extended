<?php

add_filter("Modularity/Module/ManualInput/DefaultValues", function ($values) {
  $values["link_text"] = "";
  return $values;
});

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

/**
 * Adds a "Preferred image aspect ratio" select field to "Manual inputs"
 * (field_64ff22b2d91b7) on "Manual Input" module
 */
add_action("acf/init", function () {
  acf_add_local_field([
    "key" => "field_mod_manual_input_image_aspect_ratio",
    "parent" => "field_64ff22b2d91b7", // Manual inputs
    "label" => __("Preferred image aspect ratio", "municipio-extended"),
    "name" => "image_aspect_ratio",
    "type" => "select",
    "instructions" => __(
      "Select the preferred aspect ratio for the images when displayed as segments. It might still be cropped on some screen sizes, depending on how much text you add.",
      "municipio-extended",
    ),
    "choices" => [
      "" => _x(
        "Unconstrained",
        "Preferred Image Aspect Ratio",
        "municipio-extended",
      ),
      "auto" => _x(
        "Original",
        "Preferred Image Aspect Ratio",
        "municipio-extended",
      ),
      "16/9" => _x(
        "Landscape 16:9",
        "Preferred Image Aspect Ratio",
        "municipio-extended",
      ),
      "4/3" => _x(
        "Landscape 4:3",
        "Preferred Image Aspect Ratio",
        "municipio-extended",
      ),
      "1" => _x(
        "Square 1:1",
        "Preferred Image Aspect Ratio",
        "municipio-extended",
      ),
      "3/4" => _x(
        "Portrait 3:4",
        "Preferred Image Aspect Ratio",
        "municipio-extended",
      ),
    ],
    "default_value" => "",
    "conditional_logic" => [
      [
        [
          "field" => "field_656f4b44999e9",
          "operator" => "==",
          "value" => "segment",
        ],
      ],
    ],
    "wrapper" => [
      "width" => "50%",
    ],
  ]);
});

/**
 * Allows "Preferred image aspect ratio" field to be placed beside "Image" field
 */
add_filter("acf/load_field/key=field_64ff2355d91bb", function ($field) {
  $field["wrapper"]["width"] = "50%";
  return $field;
});

// add_filter("Modularity/Module/ManualInput/data/item", function ($item, $input) {
//   $item["image_aspect_ratio"] = get_field("image_aspect_ratio", $input) ?: "";
//   return $item;
// });

// add_filter(
//   "Modularity/Module/ManualInput/data/item",
//   function ($item, $input) {
//     echo "<pre>", var_export($input, true), "</pre>";
//     exit();
//     // $item['imageAspectRatio'] = '';
//     // $image_aspect_ratio = get_field('image_aspect_ratio', $input);
//     // if (in_array($image_aspect_ratio, ['', 'auto', '16/9', '4/3', '1', '3/4'])) {
//     //     $item['imageAspectRatio'] = $image_aspect_ratio;
//     // }
//     // return $item;
//   },
//   10,
//   2,
// );

add_filter(
  "Modularity/Module/Template",
  function ($template, $slug, $is_legacy, $module) {
    if ($slug === "manualinput") {
      switch ($module->template) {
        case "segment":
          $template = "mod-manualinput-segments.blade.php";
          break;
      }
    }
    return $template;
  },
  10,
  4,
);
