<?php

function mx_render_modularity_editor_sidebar_metabox_module(
  $module_id,
  $module,
  $post_type = null,
) {
  $post_type ??= $module["post"]->postType ?? null; ?>
    <li
      class="mx-modularity-sidebar-area-module"
      x-sort:item="moduleId"
      x-bind:data-module-id="moduleId"
      x-data="{
        postId: <?php esc_attr_e(json_encode($module["postid"])); ?>,
        postTitle: <?php esc_attr_e(
          json_encode($module["post"]->title ?? null),
        ); ?>,
        moduleId: <?php esc_attr_e(json_encode($module_id)); ?>,
        postTypeName: <?php esc_attr_e(
          json_encode($post_type->name ?? null),
        ); ?>,
        postTypeLabel: <?php esc_attr_e(
          json_encode($post_type->labels->singular_name ?? null),
        ); ?>,
      }"
    >
      <input
        type="hidden"
        x-bind:name="`modularity_modules[${sidebarId}][${moduleId}][postid]`"
        x-bind:value="postId"
      >
      <input
        type="hidden"
        x-bind:name="`modularity_modules[${sidebarId}][${moduleId}][background]`"
        x-bind:value="background"
      >
      <span class="mx-modularity-sidebar-area-module-handle" x-sort:handle>
        <i class='dashicons dashicons-menu'></i>
      </span>
      <span>
        <b x-text="`${postTypeLabel}:`" x-show="postTypeLabel"></b>
        <span x-text="
          postTypeLabel || postTitle ?
            (postTitle || '<?php _ex(
              "(No title)",
              "Modularity Editor Module Field",
              "municipio-extended",
            ); ?>') :
            '<?php _ex(
              "(Deactivated module)",
              "Modularity Editor Module Field",
              "municipio-extended",
            ); ?>'
          "></span>
      </span>
      <span>
        <label>
          <input 
            type="checkbox" 
            <?php checked($module["hidden"]); ?> 
            x-bind:name="`modularity_modules[${sidebarId}][${moduleId}][hidden]`" 
            value="hidden"
          >
          <?php _ex(
            "Hidden",
            "Modularity Editor Module Field",
            "municipio-extended",
          ); ?>
        </label>
      </span>
      <span>
        <label>
          <?php _ex(
            "Width:",
            "Modularity Editor Module Field",
            "municipio-extended",
          ); ?>
        </label>
          <select x-bind:name="`modularity_modules[${sidebarId}][${moduleId}][columnWidth]`">
            <option value="" <?php selected(
              $module["columnWidth"] ?? "",
              "",
            ); ?>><?php _e("Inherit", "modularity"); ?></option>
            <option value="grid-md-12" <?php selected(
              $module["columnWidth"] ?? "",
              "grid-md-12",
            ); ?>>100%</option>
            <option value="grid-md-9" <?php selected(
              $module["columnWidth"] ?? "",
              "grid-md-9",
            ); ?>>75%</option>
            <option value="grid-md-8" <?php selected(
              $module["columnWidth"] ?? "",
              "grid-md-8",
            ); ?>>66%</option>
            <option value="grid-md-6" <?php selected(
              $module["columnWidth"] ?? "",
              "grid-md-6",
            ); ?>>50%</option>
            <option value="grid-md-4" <?php selected(
              $module["columnWidth"] ?? "",
              "grid-md-4",
            ); ?>>33%</option>
            <option value="grid-md-3" <?php selected(
              $module["columnWidth"] ?? "",
              "grid-md-3",
            ); ?>>25%</option>
        </select>
      </span>
      <button 
        type="button" 
        class="mx-admin-icon-button"
        x-bind:disabled="!postId && !postTypeName"
        x-on:click="
          window.Modularity.Editor.Module.editingModule = $el.closest('li');
          window.Modularity.Editor.Thickbox.postAction = postId ? 'edit' : 'add';
          window.Modularity.Prompt.Modal.open(postId ? `<?php esc_attr_e(
            admin_url(),
          ); ?>post.php?post=${postId}&action=edit&is_thickbox=true` : `<?php esc_attr_e(
  admin_url(),
); ?>post-new.php?post_type=${postTypeName}&is_thickbox=true`)
        "
      >
        <i class="dashicons dashicons-edit"></i>
        <span class="screen-reader-text"><?php _ex(
          "Edit",
          "Modularity Editor Module Field",
          "municipio-extended",
        ); ?></span>
      </button>
      <button
        type="button"
        class="mx-admin-icon-button"
        x-bind:disabled="!postTypeName"
        x-on:click="
          // Modularity.Editor.Module.constructor.prototype.isEditingModule = () => window.editingModule;
          window.Modularity.Editor.Module.editingModule = $el.closest('li');
          window.Modularity.Editor.Thickbox.postAction = 'import';
          window.Modularity.Prompt.Modal.open(`<?php esc_attr_e(
            admin_url(),
          ); ?>edit.php?post_type=${postTypeName}&is_thickbox=true`)
        "
      >
        <i class="dashicons dashicons-download"></i>
        <span class="screen-reader-text"><?php _ex(
          "Import",
          "Modularity Editor Module Field",
          "municipio-extended",
        ); ?></span>
      </button>
      <button type="button" class="mx-admin-icon-button" x-on:click="$el.closest('li').remove()">
        <i class="dashicons dashicons-trash"></i>
        <span class="screen-reader-text"><?php _ex(
          "Remove",
          "Modularity Editor Module Field",
          "municipio-extended",
        ); ?></span>
      </button>
    </li>
  <?php
}

add_action(
  "admin_menu",
  function () {
    if (!mx_module_groups_enabled()) {
      return;
    }
    $menu_slug = "modularity-editor";
    $parent_slug = "options.php";
    $screen = get_plugin_page_hookname($menu_slug, $parent_slug);
    $screen = convert_to_screen($screen);
    $page = $screen->id;
    add_action(
      "add_meta_boxes_" . $page,
      function () use ($page) {
        global $wp_registered_sidebars;
        global $wp_meta_boxes;
        foreach ($wp_registered_sidebars as $sidebar) {
          $id = "modularity-mb-" . $sidebar["id"];
          if (!isset($wp_meta_boxes[$page]["normal"]["low"][$id])) {
            continue;
          }
          $wp_meta_boxes[$page]["normal"]["low"][$id]["callback"] =
            "mx_render_modularity_editor_sidebar_metabox";
        }
        if (
          isset(
            $wp_meta_boxes[$page]["side"]["default"]["modularity-mb-modules"],
          )
        ) {
          $wp_meta_boxes[$page]["side"]["default"]["modularity-mb-modules"][
            "callback"
          ] = "mx_render_modularity_editor_modules_metabox";
        }
      },
      11,
    );
  },
  11,
);

function mx_render_modularity_editor_modules_metabox($post, $args) {
  $enabled = \Modularity\ModuleManager::$enabled;
  $available = \Modularity\ModuleManager::$available;
  $deprecated = \Modularity\ModuleManager::$deprecated;

  $modules = [];
  if (is_array($enabled) && !empty($enabled)) {
    foreach ($enabled as $module) {
      if (isset($available[$module]) && !in_array($module, $deprecated)) {
        $modules[$module] = apply_filters(
          "Modularity/Editor/SidebarIncompability",
          $available[$module],
          $module,
        );
      }
    }
  }

  uasort($modules, function ($a, $b) {
    if ($a["labels"]["name"] === $b["labels"]["name"]) {
      return 0;
    }
    return $a["labels"]["name"] < $b["labels"]["name"] ? -1 : 1;
  });
  ?>
    <div
      x-data
      class="modularity-modules"
      x-sort
      x-sort:config="{
        group: {
          name: 'mx-modularity-modules',
          pull: 'clone',
          put: false
        },
        sort: false,
      }"
    >
      <?php foreach ($modules as $moduleId => $module): ?>
        <div
          x-sort:item="<?php esc_attr_e(json_encode($moduleId)); ?>"
          class="modularity-module mx-modularity-modules-item"
          data-module-id="<?php echo $moduleId; ?>"
          data-sidebar-incompability='<?php echo isset(
            $module["sidebar_incompability"],
          ) && is_array($module["sidebar_incompability"])
            ? json_encode($module["sidebar_incompability"])
            : ""; ?>'
        >
          <span class="modularity-module-icon">
              <?php echo modularity_decode_icon($module); ?>
          </span>
          <span class="modularity-module-name"><?php echo $module["labels"][
            "name"
          ]; ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  <?php
}

function mx_render_modularity_editor_sidebar_metabox($post, $args) {
  global $post; // TODO: Check if we can remove this

  if (\Modularity\Helper\Post::isArchive()) {
    global $archive;
    $options = get_option("modularity_" . $archive . "_sidebar-options");
    $modules = get_option("modularity_" . $archive . "_modules");
  } else {
    if ($post) {
      $options = get_post_meta($post->ID, "modularity-sidebar-options", true);
      $modules = get_post_meta($post->ID, "modularity-modules", true);
    } else {
      if (!isset($_GET["id"]) || empty($_GET["id"])) {
        throw new \Error("Get paramter ID is empty.");
      }
      if (is_numeric($_GET["id"])) {
        $options = get_post_meta(
          $_GET["id"],
          "modularity-sidebar-options",
          true,
        );
        $modules = get_post_meta($_GET["id"], "modularity-modules", true);
      } else {
        $options = get_option("modularity_" . $_GET["id"] . "_sidebar-options");
        $modules = get_option("modularity_" . $_GET["id"] . "_modules");
      }
    }
  }

  $sidebar = $args["args"]["sidebar"]["id"];
  $options = $options[$args["args"]["sidebar"]["id"]] ?? [];
  $modules = $modules[$args["args"]["sidebar"]["id"]] ?? [];
  $modules = array_map(function ($entry) {
    $entry["post"] = $entry["postid"] ? mx_get_post($entry["postid"]) : null;
    return $entry;
  }, $modules);
  $groups = mx_group_modules($modules);
  if (empty($groups)) {
    $groups = [
      [
        "background" => null,
        "modules" => [],
      ],
    ];
  }
  $supports_backgrounds = mx_sidebar_supports_module_group_backgrounds(
    $sidebar,
  );
  $supports_groups = $supports_backgrounds;
  ?>

    <div
      x-data="{
        sidebarId: <?php esc_attr_e(json_encode($sidebar)); ?>,
        handleSort(item, position) {},
        async addGroup() {
          const template = this.$refs.groupTemplate;
          const clone = template.content.cloneNode(true).children[0];
          Alpine.destroyTree(clone);
          this.$root.querySelector('.mx-modularity-sidebar-area').appendChild(clone);
          await Alpine.nextTick();
          Alpine.initTree(clone);
          // Object.assign(Alpine.$data(clone), {
          //   background: '',
          // });
        },
        async addModule(droppedEl, postType) {
          const template = this.$refs.moduleTemplate;
          const clone = template.content.cloneNode(true).children[0];
          Alpine.destroyTree(clone);
          droppedEl.parentElement.insertBefore(clone, droppedEl);
          await Alpine.nextTick();
          Alpine.initTree(clone);
          Object.assign(Alpine.$data(clone), {
            postId: null,
            postTitle: '',
            moduleId: `${Math.random().toString(36).substring(2, 11)}`,
            postTypeName: postType,
            postTypeLabel: droppedEl.querySelector('.modularity-module-name').innerText,
          });
        }
      }"
    >
      <template x-ref="groupTemplate">
        <li
          class="mx-modularity-sidebar-area-group"
          x-sort:item
          x-data="<?php esc_attr_e(
            json_encode([
              "background" => "",
            ]),
          ); ?>"
        >
          <div class="mx-modularity-sidebar-area-group-header">
            <span class="mx-modularity-sidebar-area-group-handle" x-sort:handle>
              <i class='dashicons dashicons-menu'></i>
            </span>
            <label class="mx-modularity-sidebar-area-group-field">
              <?php _ex(
                "Background:",
                "Modularity Editor Module Group Field",
                "municipio-extended",
              ); ?>
              <select x-model="background">
                <?php foreach (
                  mx_get_module_group_background_options()
                  as $value => $label
                ): ?>
                  <option value="<?php echo esc_attr(
                    $value,
                  ); ?>"><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
          <ul
            class="mx-modularity-sidebar-area-module-list"
            x-sort="
              const item = $el.children[$position];
              await Alpine.nextTick();
              if(item.matches('.modularity-module')) {
                if(item.dataset.moduleId) {
                  addModule(item, item.dataset.moduleId);
                }
                item.remove();
              } else {
                let {
                  postId = null,
                  postTitle = '',
                  moduleId = `${Math.random().toString(36).substring(2, 11)}`,
                  postTypeName,
                  postTypeLabel,
                } = Alpine.$data(item);
                const data = {
                  postId,
                  postTitle,
                  moduleId,
                  postTypeName,
                  postTypeLabel,
                };
                Alpine.destroyTree(item);
                delete item._x_dataStack;
                await Alpine.nextTick();
                Alpine.initTree(item);
                Object.assign(Alpine.$data(item), data);
              }
            "
            x-sort:group="mx-modularity-modules"
            data-empty-label="<?php esc_attr_e(
              "Drag modules here. Empty groups are removed automatically.",
              "municipio-extended",
            ); ?>"
          ></ul>
        </li>
      </template>

      <template x-ref="moduleTemplate">
        <?php mx_render_modularity_editor_sidebar_metabox_module(uniqid(), [
          "postid" => null,
          "post" => null,
          "hidden" => false,
          "columnWidth" => null,
        ]); ?>
      </template>

      <div x-bind:class="{'mx-modularity-sidebar-content': true, 'mx-modularity-sidebar-content--supports-groups': supportsGroups}" x-data="<?php esc_attr_e(
        json_encode([
          "supportsBackgrounds" => $supports_backgrounds,
          "supportsGroups" => $supports_groups,
        ]),
      ); ?>">
        <ul
          class="mx-modularity-sidebar-area"
          x-data
          x-sort
        >
          <?php foreach ($groups as $group_idx => $group): ?>
            <li class="mx-modularity-sidebar-area-group" x-sort:item x-data="<?php esc_attr_e(
              json_encode([
                "background" => $group["background"],
              ]),
            ); ?>">
              <div class="mx-modularity-sidebar-area-group-header" x-show="supportsGroups">
                <span class="mx-modularity-sidebar-area-group-handle" x-sort:handle>
                  <i class='dashicons dashicons-menu'></i>
                </span>
                <label class="mx-modularity-sidebar-area-group-field" x-show="supportsBackgrounds">
                  <?php _ex(
                    "Background:",
                    "Modularity Editor Module Group Field",
                    "municipio-extended",
                  ); ?>
                  <select x-model="background">
                    <?php foreach (
                      mx_get_module_group_background_options()
                      as $value => $label
                    ): ?>
                      <option value="<?php echo esc_attr(
                        $value,
                      ); ?>"><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                  </select>
                </label>
              </div>
              <ul
                class="mx-modularity-sidebar-area-module-list"
                x-sort="
                  const item = $el.children[$position];
                  await Alpine.nextTick();
                  if(item.matches('.modularity-module')) {
                    if(item.dataset.moduleId) {
                      addModule(item, item.dataset.moduleId);
                    }
                    item.remove();
                  } else {
                    let {
                      postId = null,
                      postTitle = '',
                      moduleId = `${Math.random().toString(36).substring(2, 11)}`,
                      postTypeName,
                      postTypeLabel,
                    } = Alpine.$data(item);
                    const data = {
                      postId,
                      postTitle,
                      moduleId,
                      postTypeName,
                      postTypeLabel,
                    };
                    Alpine.destroyTree(item);
                    delete item._x_dataStack;
                    await Alpine.nextTick();
                    Alpine.initTree(item);
                    Object.assign(Alpine.$data(item), data);
                  }
                "
                x-sort:group="mx-modularity-modules"
                data-empty-label="<?php esc_attr_e(
                  "Drag modules here. Empty groups are removed automatically.",
                  "municipio-extended",
                ); ?>"
              ><?php foreach ($group["modules"] as $module_id => $module) {
                mx_render_modularity_editor_sidebar_metabox_module(
                  $module_id,
                  $module,
                );
              } ?></ul>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="mx-modularity-sidebar-area-content-actions" x-show="supportsGroups">
          <button type="button" class="button" x-on:click="addGroup"><?php _e(
            "Add group",
            "municipio-extended",
          ); ?></button>
        </div>
      </div>
      <div class="modularity-sidebar-options">
        <div class="container">
          <div class="col">
            <?php _e("Show modules", "modularity"); ?>
            <select name="modularity_sidebar_options[<?php echo $args["args"][
              "sidebar"
            ]["id"]; ?>][hook]">
              <option value="before" <?php selected(
                "before",
                isset($options["hook"]) ? $options["hook"] : "",
                true,
              ); ?>><?php _e("before", "modularity"); ?></option>
              <option value="after" <?php selected(
                "after",
                isset($options["hook"]) ? $options["hook"] : "",
                true,
              ); ?>><?php _e("after", "modularity"); ?></option>
            </select>
            widgets
          </div>
          <div class="col">
            <label>
              <input type="checkbox" value="true" name="modularity_sidebar_options[<?php echo $args[
                "args"
              ]["sidebar"][
                "id"
              ]; ?>][hide_widgets]" <?php checked(true, isset($options["hide_widgets"]), true); ?>>
              <?php _e("Hide global widgets", "modularity"); ?>
            </label>
          </div>
        </div>
      </div>
    </div>
  <?php
}
