<?php

add_filter(
  "Modularity/Display/BeforeModule",
  function ($beforeModule, $args, $postType, $postId) {
    if (preg_match("/^(.*?)>(.*)$/m", $beforeModule, $matches)) {
      $attrs = apply_filters(
        "mx/module_wrapper_attrs",
        [],
        $args,
        $postType,
        $postId,
      );
      $beforeModule = $matches[1] . mx_attrs($attrs) . ">" . $matches[2];
    }
    return $beforeModule;
  },
  10,
  4,
);
