<?php
add_filter('admin_init', function() {
  if (current_user_can('administrator')) {
    return;
  }
  if(current_user_can('editor')) {
    global $menu;
    global $submenu;
    $roleObject = get_role('editor');
    // Remove all current capabilities
    $currentCapabilities = $roleObject->capabilities;
    foreach($currentCapabilities as $cap=>$value) {
      $roleObject->remove_cap($cap);
    }
    // Add allowed capabilities
    $allowedCapabilities = array(
      'moderate_comments',
      'manage_categories',
      'manage_links',
      'upload_files',
      'unfiltered_html',
      'edit_posts',
      'edit_others_posts',
      'edit_published_posts',
      'publish_posts',
      'edit_pages',
      'read',
      'level_7',
      'level_6',
      'level_5',
      'level_4',
      'level_3',
      'level_2',
      'level_1',
      'level_0',
      'edit_others_pages',
      'edit_published_pages',
      'publish_pages',
      'delete_pages',
      'delete_others_pages',
      'delete_published_pages',
      'delete_posts',
      'delete_others_posts',
      'delete_published_posts',
      'delete_private_posts',
      'edit_private_posts',
      'read_private_posts',
      'delete_private_pages',
      'edit_private_pages',
      'read_private_pages',
      'edit_module',
      'edit_modules',
      'edit_other_modules',
      'publish_modules',
      'read_modules',
      'delete_module',
      'edit_theme_options',
      'manage_options',
      'gform_full_access',
    );
    foreach($allowedCapabilities as $key=>$cap) {
      $roleObject->add_cap($cap, true);
    }
    // Clean up admin menu and submenus
    $allowedMenuItems = array(
      // Posts
      'edit.php',
      'post-new.php',
      'edit-tags.php?taxonomy=category',
      'edit-tags.php?taxonomy=post_tag',
      // Media
      'upload.php',
      'media-new.php',
      // Pages
      'nestedpages',
      'edit.php?post_type=page',
      'post-new.php?post_type=page',
      'options.php?page=modularity-editor&id=single-page',
      // Events
      'edit.php?post_type=event',
      // Anslag
      'edit.php?post_type=anslag',
      'post-new.php?post_type=anslag',
      // Users
      'profile.php',
      // Forms
      'edit.php?post_type=form-submissions',
      // todo: include auoload/redirection-permissions?
      '&#47;wp-admin/tools.php?page=redirection.php'
    );
    foreach($menu as $key=>$menuItem) {
      if(!in_array($menuItem[2], $allowedMenuItems) || !in_array($menuItem[1], $allowedCapabilities)) {
        unset($menu[$key]);
      } else {
        if(isset($submenu[$menuItem[2]])) {
          foreach($submenu[$menuItem[2]] as $k1=>$subMenuItem) {
            if(!in_array($subMenuItem[2], $allowedMenuItems) || !in_array($subMenuItem[1], $allowedCapabilities)) {
              unset($submenu[$menuItem[2]][$k1]);
            }
          }
        }
      }
    }
  }
});