<?php

use DiDom\Document;
use Illuminate\Support\HtmlString;

function mx_new_instance_without_constructor($class) {
  $reflector = new ReflectionClass($class);
  return $reflector->newInstanceWithoutConstructor();
}

function mx_get_module_directory($module) {
  global $mx_modularity_display_instance;
  if (empty($mx_modularity_display_instance)) {
    $mx_modularity_display_instance = mx_new_instance_without_constructor(
      "Modularity\\Display",
    );
  }
  return $mx_modularity_display_instance->getModuleDirectory($module);
}

function mx_get_default_module_view_path($module) {
  return MODULARITY_PATH .
    "source/php/Module/" .
    mx_get_module_directory($module) .
    "/views";
}

function mx_plain_text($string, $options = []) {
  if (empty($string)) {
    return "";
  }
  $document = new Document($string);
  if (!empty($options["exclude"])) {
    $nodes = $document->find(implode(",", (array) $options["exclude"]));
    foreach ($nodes as $node) {
      $node->remove();
    }
  }
  $string = $document->text();
  return $string;
}

/**
 * clsx ported from https://raw.githubusercontent.com/lukeed/clsx/master/src/index.js
 */
function _clsx_val($mix) {
  $str = "";
  if (is_string($mix) || is_numeric($mix)) {
    $str .= $mix;
  } elseif (is_array($mix)) {
    foreach ($mix as $k => $value) {
      if (is_numeric($k)) {
        if ($mix[$k]) {
          if ($y = _clsx_val($mix[$k])) {
            $str && ($str .= " ");
            $str .= $y;
          }
        }
      } else {
        if ($value) {
          $str && ($str .= " ");
          $str .= $k;
        }
      }
    }
  }
  return $str;
}

function clsx() {
  $str = "";
  $len = func_num_args();
  $args = func_get_args();
  for ($i = 0; $i < $len; $i++) {
    $tmp = $args[$i];
    if ($tmp) {
      if ($x = _clsx_val($tmp)) {
        $str && ($str .= " ");
        $str .= $x;
      }
    }
  }
  return $str;
}

function mx_coalesce_string($values, $glue = " ") {
  foreach ($values as $value) {
    if (is_array($value)) {
      $value = implode($glue, $value);
    }
    if (!empty($value)) {
      $value = trim((string) $value);
    }
    if (!empty($value)) {
      return $value;
    }
  }
  return "";
}

function mx_attrs(...$attrs) {
  return new MunicipioExtended\Model\AttributeBag(...$attrs);
}

function mx_error_log(...$messages) {
  if (class_exists("Lumbermill")) {
    Lumbermill::debug(...$messages);
  } elseif (defined("MX_DEBUG") && MX_DEBUG) {
    foreach ($messages as $message) {
      error_log(var_export($message, true));
    }
  }
}

function mx_safe_html($string) {
  return new HtmlString($string);
}

function mx_get_regular_post_types($output = "names") {
  $post_types = get_post_types(
    [
      "public" => true,
    ],
    $output,
  );
  if ($output === "names") {
    $post_types = array_diff($post_types, ["attachment"]);
    $post_types = array_filter($post_types, function ($post_type) {
      return strpos($post_type, "mod-") === false;
    });
  }
  if ($output === "objects") {
    $post_types = array_filter($post_types, function ($post_type) {
      return $post_type->name !== "attachment";
    });
    $post_types = array_filter($post_types, function ($post_type) {
      return strpos($post_type->name, "mod-") === false;
    });
  }
  return $post_types;
}

function mx_array_replace($array, $search, $replace) {
  return array_map(function ($value) use ($search, $replace) {
    return $value === $search ? $replace : $value;
  }, $array);
}

function mx_css_vars($vars) {
  $css = "";
  foreach ($vars as $key => $value) {
    if (is_null($value)) {
      continue;
    }
    $css .= "$key:$value;";
  }
  return $css;
}
