<?php

namespace MunicipioExtended\Model;

class WpPostType extends Model {
  protected $name;
  protected \WP_Post_Type $postType;

  public function __construct($post_type, $data = []) {
    if (is_string($post_type)) {
      $post_type = get_post_type_object($post_type);
    }
    if ($post_type instanceof \WP_Post_Type) {
      $this->name = $post_type->name;
      $this->postType = $post_type;
    } else {
      throw new \InvalidArgumentException(
        "Invalid post type " . json_encode($post_type),
      );
    }
    if (!$this->postType) {
      throw new \InvalidArgumentException(
        "Post type " . json_encode($post_type) . " not found",
      );
    }
    parent::__construct($data);
  }

  public function get(string $name): mixed {
    $value = parent::get($name);
    if ($value !== null) {
      return $value;
    }
    if (isset($this->postType->$name)) {
      return $this->postType->$name;
    }
    return null;
  }

  public function has(string $name): bool {
    return parent::has($name) || isset($this->postType->$name);
  }

  public function getName() {
    return $this->name;
  }

  public function __toString() {
    return $this->name;
  }
}
