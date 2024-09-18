<?php

add_action("article_content_before", function () {
  $post = mx_get_post();
echo mx_render_view('section-back-button', ['post' => $post]);
  });