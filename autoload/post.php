<?php

add_filter('register_post_type_args', function ($args, $post_type) {
  if ($post_type === 'post') {
      $args['labels'] = [
          'name'                  => _x('News', 'Post type general name', 'mu-plugins'),
          'singular_name'         => _x('News', 'Post type singular name', 'mu-plugins'),
          'menu_name'             => _x('News', 'Admin Menu text', 'mu-plugins'),
          'name_admin_bar'        => _x('News', 'Add New on Toolbar', 'mu-plugins'),
          'add_new'               => __('Add news', 'mu-plugins'),
          'add_new_item'          => __('Add news', 'mu-plugins'),
          'new_item'              => __('New item', 'mu-plugins'),
          'edit_item'             => __('Edit news', 'mu-plugins'),
          'view_item'             => __('View news', 'mu-plugins'),
          'all_items'             => __('All news', 'mu-plugins'),
          'search_items'          => __('Search news', 'mu-plugins'),
          'parent_item_colon'     => __('Parent news:', 'mu-plugins'),
          'not_found'             => __('No news found.', 'mu-plugins'),
          'not_found_in_trash'    => __('No news found in Trash.', 'mu-plugins'),
          'archives'              => _x('News archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'mu-plugins'),
          'insert_into_item'      => _x('Insert into news', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'mu-plugins'),
          'uploaded_to_this_item' => _x('Uploaded to this news', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'mu-plugins'),
          'filter_items_list'     => _x('Filter news list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”. Added in 4.4', 'mu-plugins'),
          'items_list_navigation' => _x('News list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”. Added in 4.4', 'mu-plugins'),
          'items_list'            => _x('News list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”. Added in 4.4', 'mu-plugins'),
      ];
  }

  return $args;
}, 10, 2);
