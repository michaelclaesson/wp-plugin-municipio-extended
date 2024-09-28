<?php

namespace MunicipioExtended\Model;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Icon extends Model implements Htmlable, IconInterface {
  public static function getRenderParams() {
    return [
      "filled" => [
        "default" => false,
        "type" => "boolean",
        "regex" => '/^filled$/',
      ],
      "grade" => [
        "default" => 0,
        "type" => "number",
        "allow_negative" => true,
        "regex" => '/^(-?)grade-(\d+)$/',
      ],
      "optical_size" => [
        "default" => 24,
        "type" => "number",
        "regex" => '/^optical-size-(\d+)$/',
      ],
      "style" => [
        "default" => "outlined",
        "type" => "string",
        "regex" => '/^(outlined|rounded|sharp)$/',
      ],
      "weight" => [
        "default" => 400,
        "type" => "enum",
        "options" => [
          "thin" => 100,
          "extralight" => 200,
          "light" => 300,
          "normal" => 400,
          "medium" => 500,
          "semibold" => 600,
          "bold" => 700,
          // "extrabold" => 800,
          // "black" => 900,
        ],
      ],
    ];
  }

  public function __construct($input, $data = []) {
    if (!is_array($input)) {
      $input = [
        "name" => $input,
      ];
    }
    $data = array_merge($data, $input);
    parent::__construct($data);
  }

  public function __toString() {
    return $this->name;
  }

  public function get(string $name): mixed {
    $render_params = self::getRenderParams();
    $name_parts = preg_split("/\s+/", trim($name));
    $render_param_values = [];
    foreach ($name_parts as $name_part) {
      foreach ($render_params as $param_name => $param) {
        $regex = $param["regex"];
        if ($regex == null) {
          if ($param["type"] === "enum") {
            $regex =
              "/^(" . implode("|", array_keys($param["options"])) . ')$/';
          }
        }
        if (preg_match($param["regex"], $name_part, $matches)) {
          if ($param["type"] === "boolean") {
            $render_param_values[$param_name] = true;
          } elseif ($param["type"] === "number") {
            if ($param["allow_negative"] ?? false) {
              $render_param_values[$param_name] = $matches[1]
                ? -intval($matches[2])
                : intval($matches[2]);
            } else {
              $render_param_values[$param_name] = intval($matches[1]);
            }
          } elseif ($param["type"] === "enum") {
            $render_param_values[$param_name] = $param["options"][$matches[1]];
          } else {
            $render_param_values[$param_name] = $matches[1];
          }
        }
      }
    }
    if (count($render_param_values) > 0) {
      return $this->withRenderParams($render_param_values);
    }
    return parent::get($name);
  }

  public function withRenderParams($values) {
    return new self(array_merge($this->data, ["renderParams" => $values]));
  }

  public function getResolvedRenderParamValue($name) {
    $render_params = self::getRenderParams();
    $param = $render_params[$name] ?? null;
    if (!$param) {
      throw new \InvalidArgumentException("No render param with name '$name'");
    }
    $value = $this->data["renderParams"][$name] ?? null;
    if ($param["type"] === "enum") {
      $value = $param["options"][$value] ?? null;
    }
    if ($value === null) {
      $value = $param["default"] ?? null;
    }
    return $value;
  }

  public function getSourceFileName() {
    $render_params = self::getRenderParams();

    $style = $this->getResolvedRenderParamValue("style");

    $file = $style;

    $variant = "";

    $weight = $this->getResolvedRenderParamValue("weight");
    if ($weight != $render_params["weight"]["default"]) {
      $variant .= "wght{$weight}";
    }

    $grade = $this->getResolvedRenderParamValue("grade");
    if ($grade != $render_params["grade"]["default"]) {
      if ($grade < 0) {
        $grade = "N" . abs($grade);
      }
      $variant .= "grad{$grade}";
    }

    $filled = $this->getResolvedRenderParamValue("filled");
    if ($filled) {
      $variant .= "fill1";
    }

    if ($variant) {
      $file .= "_{$variant}";
    }

    $optical_size = $this->getResolvedRenderParamValue("optical_size");
    $file .= "_{$optical_size}px";

    return $file;
  }

  public function getSourceFile() {
    return MUNICIPIO_EXTENDED_PATH .
      "/static/materialsymbols/" .
      $this->getSourceFileName() .
      ".xml";
  }

  protected function ensureFile() {
    if (!file_exists($this->getFilePath())) {
      $this->generateFile();
    }
  }

  protected function generateFile() {
    $source_file = $this->getSourceFile();
    $file = fopen($source_file, "r");
    if (!$file) {
      throw new \Exception("Could not open file $source_file");
    }
    while (!feof($file)) {
      $line = fgets($file);
      $prefix = $this->data["name"] . ":";
      if (strpos($line, $prefix) === 0) {
        $found = substr($line, strlen($prefix));
        break;
      }
    }
    fclose($file);
    if ($found) {
      wp_mkdir_p(wp_get_upload_dir()["basedir"] . "/mx/materialsymbols");
      file_put_contents($this->getFilePath(), $found);
    }
  }

  public function getFilePath() {
    return wp_get_upload_dir()["basedir"] .
      "/mx/materialsymbols/" .
      $this->data["name"] .
      "_" .
      $this->getSourceFileName() .
      ".svg";
  }

  public function getUrl() {
    $this->ensureFile();
    return wp_get_upload_dir()["baseurl"] .
      "/mx/materialsymbols/" .
      $this->data["name"] .
      "_" .
      $this->getSourceFileName() .
      ".svg";
  }

  public function render($props = []) {
    return new HtmlString(
      mx_render_view("mxui.icon", array_merge(["icon" => $this, $props])),
    );
  }

  public function toHtml() {
    return $this->render()->toHtml();
  }
}
