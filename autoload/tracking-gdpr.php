<?php

add_filter(
  "wstg_content_iframe_replacement",
  function ($content, $data) {
    $height = $data["node"]->getAttribute("height") ?: null;
    return '<div class="tailwind">' .
      mx_render_view("mxui.iframe", [
        "service" => $data["video_service"],
        "id" => $data["video_id"],
        "url" => $data["url"],
        "height" => $height,
      ]) .
      "</div>";
  },
  10,
  2,
);
