<?php

use ComponentLibrary\Init;

function mx_render_view($view, $data) {
  $viewPaths = \Municipio\Helper\Template::getViewPaths();

  if (!is_array($viewPaths) || empty($viewPaths)) {
    throw new \Exception(
      "No view paths registered, please register at least one.",
    );
  }

  $componentLibrary = new Init([]);
  $bladeEngine = $componentLibrary->getEngine();

  try {
    $markup = $bladeEngine
      ->makeView(
        $view,
        array_merge($data, ["errorMessage" => false]),
        [],
        $viewPaths,
      )
      ->render();

    // Adds the option to make html more readable.
    // This is a option that is intended for permanent
    // use. But cannot be implemented due to some html
    // issues.
    if (class_exists("tidy") && isset($_GET["tidy"])) {
      $tidy = new \tidy();

      $tidy->parseString(
        $markup,
        [
          "indent" => true,
          "output-xhtml" => false,
          "wrap" => PHP_INT_MAX,
        ],
        "utf8",
      );

      $tidy->cleanRepair();

      return $tidy;
    } else {
      return $markup;
    }
  } catch (\Throwable $e) {
    $bladeEngine->errorHandler($e)->print();
  }

  return false;
}
