<?php

namespace MunicipioExtended\Model;

class AttributeBag implements \Illuminate\Contracts\Support\Htmlable {
  protected $data;

  public function __construct(...$data) {
    $data = array_filter($data);
    $data = array_merge(...$data);
    $this->data = $data;
  }

  public function toHtml() {
    $arr = array_map(
      function ($value, $name) {
        if (is_array($value)) {
          $value = _clsx_val($value);
        }
        if (
          (strpos($name, "aria-") === 0 || strpos($name, "data-") === 0) &&
          !is_string($value)
        ) {
          $value = json_encode($value);
        }
        if (is_null($value) || $value === false) {
          return false;
        }
        if ($value === true) {
          return $name;
        }
        return $name .
          '="' .
          htmlspecialchars($value, ENT_COMPAT | ENT_SUBSTITUTE | ENT_HTML5) .
          '"';
      },
      $this->data,
      array_keys($this->data),
    );
    $arr = array_filter($arr);
    return implode(" ", $arr);
  }

  /**
   * Determine if the given HTML string is empty.
   *
   * @return bool
   */
  public function isEmpty() {
    return preg_match('/^\s*$/', $this->toHtml());
  }

  /**
   * Determine if the given HTML string is not empty.
   *
   * @return bool
   */
  public function isNotEmpty() {
    return !$this->isEmpty();
  }

  public function __toString() {
    return $this->toHtml();
  }
}
