<?php

add_filter('register_post_type_args', function ($args, $post_type) {
  if ($post_type === 'post') {
      $args['labels'] = [
          'name'                  => _x('News', 'Post type general name', 'municipio-extended'),
          'singular_name'         => _x('News', 'Post type singular name', 'municipio-extended'),
          'menu_name'             => _x('News', 'Admin Menu text', 'municipio-extended'),
          'name_admin_bar'        => _x('News', 'Add New on Toolbar', 'municipio-extended'),
          'add_new'               => __('Add news', 'municipio-extended'),
          'add_new_item'          => __('Add news', 'municipio-extended'),
          'new_item'              => __('New item', 'municipio-extended'),
          'edit_item'             => __('Edit news', 'municipio-extended'),
          'view_item'             => __('View news', 'municipio-extended'),
          'all_items'             => __('All news', 'municipio-extended'),
          'search_items'          => __('Search news', 'municipio-extended'),
          'parent_item_colon'     => __('Parent news:', 'municipio-extended'),
          'not_found'             => __('No news found.', 'municipio-extended'),
          'not_found_in_trash'    => __('No news found in Trash.', 'municipio-extended'),
          'archives'              => _x('News archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'municipio-extended'),
          'insert_into_item'      => _x('Insert into news', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'municipio-extended'),
          'uploaded_to_this_item' => _x('Uploaded to this news', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'municipio-extended'),
          'filter_items_list'     => _x('Filter news list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”. Added in 4.4', 'municipio-extended'),
          'items_list_navigation' => _x('News list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”. Added in 4.4', 'municipio-extended'),
          'items_list'            => _x('News list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”. Added in 4.4', 'municipio-extended'),
      ];
  }

  return $args;
}, 10, 2);
