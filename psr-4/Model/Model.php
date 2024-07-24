<?php

namespace MunicipioExtended\Model;

class Model implements \ArrayAccess {
  protected $data;

  public function __construct($data = []) {
    $this->data = $data;
  }

  public function has(string $name): bool {
    $method_name = "get" . ucfirst($name);
    if (method_exists($this, $method_name)) {
      $reflection = new \ReflectionMethod($this, $method_name);
      return $reflection->isPublic();
    }
    return isset($this->data[$name]);
  }

  public function get(string $name): mixed {
    $method_name = "get" . ucfirst($name);
    if (method_exists($this, $method_name)) {
      $reflection = new \ReflectionMethod($this, $method_name);
      if ($reflection->isPublic()) {
        return $this->$method_name();
      }
    }
    return $this->data[$name] ?? null;
  }

  public function __isset(string $name): bool {
    return $this->has($name);
  }

  public function __get(string $name): mixed {
    return $this->get($name);
  }

  public function offsetExists(mixed $offset): bool {
    $prop_name = acf_str_camel_case($offset);
    return isset($this->$prop_name);
  }

  public function offsetGet(mixed $offset): mixed {
    $prop_name = acf_str_camel_case($offset);
    return $this->$prop_name;
  }

  public function offsetSet(mixed $offset, mixed $value): void {
    throw new \BadMethodCallException("Setting properties is not allowed");
  }

  public function offsetUnset(mixed $offset): void {
    throw new \BadMethodCallException("Unsetting properties is not allowed");
  }
}
