<?php

add_filter(
  "wstg_content_iframe_replacement",
  function ($content, $data) {
    return '<div class="tailwind">' .
      mx_render_view("mxui.iframe", [
        "service" => $data["video_service"],
        "id" => $data["video_id"],
        "url" => $data["url"],
      ]) .
      "</div>";
  },
  10,
  2,
);
