<?php

namespace MunicipioExtended\Model;

class WpPost extends Model {
  protected $post_id;
  protected \WP_Post $post;

  const ICON_FIELD_NAME = "page_navigation_icon";
  const THEME_COLOR_FIELD_NAME = "page_apperance_theme_color";
  const MENU_TITLE_FIELD_NAME = "custom_menu_title";
  const MENU_DESCRIPTION_FIELD_NAME = "page_navigation_description";

  public function __construct($post, $data = []) {
    if (is_numeric($post)) {
      $this->post_id = $post;
      $this->post = get_post($post);
    } elseif ($post instanceof \WP_Post) {
      $this->post_id = $post->ID;
      $this->post = $post;
    } else {
      throw new \InvalidArgumentException("Invalid post");
    }
    if (!$this->post) {
      throw new \InvalidArgumentException("Post $post not found");
    }
    parent::__construct($data);
  }

  public function get(string $name): mixed {
    $value = parent::get($name);
    if ($value !== null) {
      return $value;
    }
    if (isset($this->post->$name)) {
      return $this->post->$name;
    }
    $post_prop = "post_" . $name;
    if (isset($this->post->$post_prop)) {
      return $this->post->$post_prop;
    }
    return null;
  }

  public function has(string $name): bool {
    $post_prop = "post_" . $name;
    return parent::has($name) ||
      isset($this->post->$name) ||
      isset($this->post->$post_prop);
  }

  protected function getField($field, ...$args) {
    return get_field($field, $this->post_id, ...$args);
  }

  public function getIcon() {
    $value = $this->getField(static::ICON_FIELD_NAME);
    if (!$value) {
      return null;
    }
    return mx_get_icon($value);
  }

  public function getImageId() {
    return get_post_thumbnail_id($this->post);
  }

  public function getImage() {
    return mx_get_image($this->imageId);
  }

  public function getParentId() {
    return $this->post->post_parent;
  }

  public function getParent() {
    if (!$this->parentId) {
      return null;
    }
    return mx_get_post($this->parentId);
  }

  public function getOwnThemeColor() {
    return $this->getField(static::THEME_COLOR_FIELD_NAME);
  }

  public function getThemeColor() {
    return $this->ownThemeColor ?: $this->parent->themeColor ?? null;
  }

  public function getMenuTitle() {
    return $this->getField(static::MENU_TITLE_FIELD_NAME) ?: $this->title;
  }

  public function getMenuDescription() {
    return $this->getField(static::MENU_DESCRIPTION_FIELD_NAME);
  }

  public function getUrl() {
    return get_permalink($this->post);
  }

  public function getHref() {
    return get_permalink($this->post);
  }

  public function getId() {
    return $this->post_id;
  }
}
