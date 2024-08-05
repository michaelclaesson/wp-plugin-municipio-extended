<?php

namespace MunicipioExtended\Model;

class WpImage extends WpPost {
  protected $size;

  public function __construct($post, $size = "thumbnail", $data = []) {
    parent::__construct($post, $data);
    $this->size = $size;
  }

  protected static function getAllImageSizes() {
    global $_wp_additional_image_sizes;
    $default_image_sizes = get_intermediate_image_sizes();
    $additional_image_sizes = array_keys($_wp_additional_image_sizes);
    return array_merge($default_image_sizes, $additional_image_sizes);
  }

  public function toSize(string $size) {
    return new self($this->post_id, $size, $this->data);
  }

  public function get(string $name): mixed {
    $image_sizes = self::getAllImageSizes();
    if (in_array($name, $image_sizes)) {
      return $this->toSize($name);
    }
    return parent::get($name);
  }

  public function has(string $name): bool {
    $image_sizes = self::getAllImageSizes();
    return in_array($name, $image_sizes) || parent::has($name);
  }

  public function getSrc() {
    return wp_get_attachment_image_url($this->post_id, $this->size);
  }

  public function getSrcset() {
    return wp_get_attachment_image_srcset($this->post_id, $this->size);
  }

  public function getAlt() {
    return get_post_meta($this->post_id, "_wp_attachment_image_alt", true);
  }
}
