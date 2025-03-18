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
        $regex = $param["regex"] ?? null;
        if (!$regex) {
          if ($param["type"] === "enum") {
            $regex =
              "/^(" . implode("|", array_keys($param["options"])) . ')$/';
          }
        }
        if (preg_match($regex, $name_part, $matches)) {
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

  protected function getPack() {
    $render_params = self::getRenderParams();

    $style = $this->getResolvedRenderParamValue("style");

    $pack = $style;

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
      $pack .= "_{$variant}";
    }

    $optical_size = $this->getResolvedRenderParamValue("optical_size");
    $pack .= "_{$optical_size}px";

    return $pack;
  }

  public function getUrl() {
    $name = $this->data["name"];
    $pack = $this->getPack();
    return mx_get_materialsymbols_svg_url($name, $pack);
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
