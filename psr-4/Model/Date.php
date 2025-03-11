<?php

namespace MunicipioExtended\Model;

class Date extends Model {
  protected int $value;
  protected $format;

  protected static function getAvailableFormats() {
    return [
      "month" => "n",
      "monthPadded" => "m",
      "monthShort" => "M",
      "monthLong" => "F",
      "day" => "j",
      "dayPadded" => "d",
      "dayShort" => "D",
      "dayLong" => "l",
      "date" => get_option("date_format"),
      "time" => get_option("time_format"),
      "dateTime" => get_option("date_format") . " " . get_option("time_format"),
    ];
  }

  public function __construct(int $value, $format = "date", $data = []) {
    $this->value = $value;
    $this->format = $format;
    parent::__construct($data);
  }

  public function toFormat($format) {
    return new self($this->value, $format, $this->data);
  }

  public function get(string $name): mixed {
    if (array_key_exists($name, self::getAvailableFormats())) {
      return $this->format(self::getAvailableFormats()[$name]);
    }
    return parent::get($name);
  }

  public function has(string $name): bool {
    if (array_key_exists($name, self::getAvailableFormats())) {
      return true;
    }
    return parent::has($name);
  }

  protected function format($format) {
    return wp_date(
      self::getAvailableFormats()[$format] ?? $format,
      $this->value,
    );
  }

  public function __toString() {
    return $this->format($this->format);
  }
}
