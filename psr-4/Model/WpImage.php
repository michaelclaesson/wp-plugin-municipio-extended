<?php

namespace MunicipioExtended\Model;

class WpImage extends Model {
  protected $attachment_id;
  protected $attachment;
  protected $size;

  public function __construct($attachment, $size = "thumbnail", $data = []) {
    if (is_numeric($attachment)) {
      $this->attachment_id = $attachment;
      $this->attachment = get_post($attachment);
    } elseif ($attachment instanceof \WP_Post) {
      $this->attachment_id = $attachment->ID;
      $this->attachment = $attachment;
    } else {
      throw new \InvalidArgumentException("Invalid attachment");
    }
    $this->size = $size;
    parent::__construct($data);
  }

  protected static function getAllImageSizes() {
    global $_wp_additional_image_sizes;
    $default_image_sizes = get_intermediate_image_sizes();
    $additional_image_sizes = array_keys($_wp_additional_image_sizes);
    return array_merge($default_image_sizes, $additional_image_sizes);
  }

  public function toSize(string $size) {
    return new self($this->attachment_id, $size, $this->data);
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
    return wp_get_attachment_image_url($this->attachment_id, $this->size);
  }

  public function getSrcset() {
    return wp_get_attachment_image_srcset($this->attachment_id, $this->size);
  }

  public function getAlt() {
    return get_post_meta(
      $this->attachment_id,
      "_wp_attachment_image_alt",
      true,
    );
  }
}
