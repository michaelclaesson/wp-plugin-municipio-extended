<?php

namespace MunicipioExtended\Model;

class WpPostMeta extends Model {
  protected $post_id;

  public function __construct($post_id, $data = []) {
    if (is_numeric($post_id)) {
      $this->post_id = (int) $post_id;
    } elseif ($post_id instanceof \WP_Post) {
      $this->post_id = $post_id->ID;
    } else {
      throw new \InvalidArgumentException("Invalid post");
    }
    parent::__construct($data);
  }

  public function has(string $name): bool {
    $value = parent::has($name);
    if (!$value) {
      $value = metadata_exists("post", $this->post_id, $name);
    }
    return $value;
  }

  public function get(string $name): mixed {
    $value = parent::get($name);
    if ($value !== null) {
      return $value;
    }
    return get_post_meta($this->post_id, $name, true);
  }

  public function offsetExists(mixed $offset): bool {
    // $prop_name = acf_str_camel_case($offset);
    return isset($this->$offset);
  }

  public function offsetGet(mixed $offset): mixed {
    // $prop_name = acf_str_camel_case($offset);
    return $this->$offset;
  }
}
