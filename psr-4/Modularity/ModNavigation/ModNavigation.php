<?php

namespace MunicipioExtended\Modularity\ModNavigation;

class ModNavigation extends \Modularity\Module {
  public $slug = "navigation";
  public $supports = [];

  public function init() {
    $this->nameSingular = _x(
      "Navigation",
      "Post Type Singular Name",
      "municipio-extended",
    );
    $this->namePlural = _x(
      "Navigation modules",
      "Post Type General Name",
      "municipio-extended",
    );
    $this->description = __(
      "Outputs a menu or manually selected links",
      "municipio-extended",
    );

    // Arrow Circle Right from https://fluenticons.co/
    $icon_svg =
      '<svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.001c5.524 0 10 4.477 10 10s-4.476 10-10 10c-5.522 0-10-4.477-10-10s4.478-10 10-10Zm.781 5.469-.084-.073a.75.75 0 0 0-.883-.007l-.094.08-.072.084a.75.75 0 0 0-.007.883l.08.094 2.719 2.72H7.75l-.102.006a.75.75 0 0 0-.641.642L7 12l.007.102a.75.75 0 0 0 .641.641l.102.007h6.69l-2.72 2.72-.073.085a.75.75 0 0 0 1.05 1.05l.083-.073 4.002-4 .072-.085a.75.75 0 0 0 .008-.882l-.08-.094-4-4.001-.085-.073.084.073Z" fill="#212121"/></svg>';
    $this->icon = "data:image/svg+xml;base64," . base64_encode($icon_svg);
  }

  /**
   * Get the template file for the module
   * @return string
   */
  public function template(): string {
    return "mod-navigation.blade.php";
  }

  protected function getField($field, ...$args) {
    return get_field($field, $this->ID, ...$args);
  }

  protected function getItems() {
    switch ($this->getField("mod_navigation_source")) {
      case "children":
        $depth =
          $this->getField("mod_navigation_depth") ?:
          ($this->getField("mod_navigation_format") === "tree"
            ? 2
            : 1);
        return $this->getChildren($depth);
      case "siblings":
        return $this->getSiblings();
      case "manual":
        return $this->getManualItems();
      case "menu":
        return $this->getMenus();
      default:
        return [];
    }
  }

  protected function getChildren($depth = 1, $post_id = null) {
    if ($depth <= 0) {
      return null;
    }
    $post = get_post($post_id);
    if (!$post) {
      return [];
    }
    $args = [
      "post_parent" => $post->ID,
      "post_type" => $post->post_type,
      "nopaging" => true,
      "post_status" => "publish",
      "orderby" => "menu_order",
      "meta_query" => [
        [
          "key" => "hide_in_menu",
          "value" => "1",
          "compare" => "!=",
        ],
      ],
    ];
    $child_posts = get_posts($args);
    $items = array_map(function ($post) use ($depth) {
      return [
        "id" => $post->ID,
        "post" => $post,
        "title" =>
          get_field("custom_menu_title", $post->ID) ?: $post->post_title,
        "href" => get_permalink($post->ID),
        "image" => mx_get_image(get_post_thumbnail_id($post)),
        "icon" => get_field("page_navigation_icon", $post->ID),
        "description" => get_field("page_navigation_description", $post->ID),
        "color" => get_field("page_apperance_theme_color", $post->ID),
        "children" => $this->getChildren($depth - 1, $post->ID),
      ];
    }, $child_posts);
    return $items;
  }

  protected function getSiblings() {
    $post = get_post();
    if (!$post) {
      return [];
    }
    $args = [
      "post_parent" => $post->post_parent,
      "post_type" => $post->post_type,
      "post__not_in" => [$post->ID],
      "nopaging" => true,
      "post_status" => "publish",
      "orderby" => "menu_order",
      "meta_query" => [
        [
          "key" => "hide_in_menu",
          "value" => "1",
          "compare" => "!=",
        ],
      ],
    ];
    $sibling_posts = get_posts($args);
    $items = array_map(function ($post) {
      return [
        "id" => $post->ID,
        "post" => $post,
        "title" =>
          get_field("custom_menu_title", $post->ID) ?: $post->post_title,
        "href" => get_permalink($post->ID),
        "image" => mx_get_image(get_post_thumbnail_id($post)),
        "icon" => get_field("page_navigation_icon", $post->ID),
        "description" => get_field("page_navigation_description", $post->ID),
        "color" => get_field("page_apperance_theme_color", $post->ID),
      ];
    }, $sibling_posts);
    return $items;
  }

  protected function getMenus() {
    $menu_slug = $this->getField("mod_navigation_menu");

    if (empty($menu_slug)) {
      return [];
    }

    $menu_items = wp_get_nav_menu_items($menu_slug);

    if (empty($menu_items)) {
      return [];
    }

    return array_map(function ($item) {
      $icon = get_post_meta($item->ID, "menu_item_icon", true);
      return [
        "title" => $item->title,
        "href" => $item->url,
        "icon" =>
          $icon ?: get_field("page_navigation_icon", $item ? $item->ID : null),
        // TODO: Add color field for case "menu" in wordpress
        // "color" => get_field("page_apperance_theme_color", $item ? $item->ID : null),
      ];
    }, $menu_items);
  }

  protected function getManualItems() {
    $items = $this->getField("mod_navigation_items");
    if (empty($items)) {
      return [];
    }
    return array_map(function ($item) {
      $post_id = url_to_postid($item["link"]["url"]);
      $post = $post_id ? get_post($post_id) : null;
      return [
        "post" => $post,
        "title" =>
          $item["link"]["title"] ?:
          get_field("custom_menu_title", $post->ID) ?:
          $post->post_title,
        "href" => $item["link"]["url"],
        "image" => $post ? mx_get_image(get_post_thumbnail_id($post)) : null,
        "icon" =>
          $item["icon"] ?:
          ($post
            ? get_field("page_navigation_icon", $post->ID)
            : null),
        "description" => $post
          ? get_field("page_navigation_description", $post->ID)
          : null,
        "color" =>
          $item["color"] ?:
          ($post
            ? get_field("page_apperance_theme_color", $post->ID)
            : null),
      ];
    }, $items);
  }

  /**
   * Get metadata for block or module.
   * @return array
   */
  protected function getFields() {
    $fields = parent::getFields();
    $fields = array_combine(
      array_map(function ($key) {
        return preg_replace("/^mod_navigation_/", "", $key);
      }, array_keys($fields)),
      $fields,
    );
    return $fields;
  }

  /**
   * Data array
   * @return array $data
   */
  public function data(): array {
    $data = (array) $this->getFields();
    $data["items"] = $this->getItems();

    // error_log(var_export($data, true));

    // $data["title"] = get_field("field_block_title", $this->ID);
    // $data["hide_title"] = $this->hideTitle;

    return $data;
  }
}
