<?php

namespace MunicipioExtended\Model;

class WpMenuItem extends WpPost {
  const ICON_FIELD_NAME = "menu_item_icon";

  public function __construct($post, $data = []) {
    parent::__construct($post, $data);
    $this->post = wp_setup_nav_menu_item($this->post);
  }

  public function getConnectedPost() {
    if ($this->type === "post_type") {
      return mx_get_post($this->object_id);
    }
  }

  public function getConnectedObject() {
    return $this->getConnectedPost();
  }

  /**
   * Menu items have a URL field so we check that first before falling back to
   * the connected object which in turn calls `get_permalink`.
   * @return string|null
   * @see WpPost::getUrl
   */
  public function getUrl() {
    return $this->post->url ?? ($this->connectedObject->url ?? null);
  }

  public function get(string $name): mixed {
    preg_match("/^(own(?=[A-Z]))?(.*)$/", $name, $matches);
    if ($matches[1] === "own") {
      $name = lcfirst($matches[2]);
      return parent::get($name);
    }
    $value = parent::get($name);
    if ($value !== null) {
      return $value;
    }
    return $this->connectedObject->get($name) ?? null;
  }

  public function has(string $name): bool {
    preg_match("/^(own(?=[A-Z]))?(.*)$/", $name, $matches);
    if ($matches[1] === "own") {
      $name = lcfirst($matches[2]);
      return parent::has($name);
    }
    return parent::has($name) || $this->connectedObject->has($name) ?? false;
  }
}
