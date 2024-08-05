<?php

namespace MunicipioExtended\Model;

class Icon extends Model {
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
}
