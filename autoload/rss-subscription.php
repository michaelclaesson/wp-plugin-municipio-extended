<?php

add_action("wp_head", function () {
  $rss_feed_url = get_bloginfo("rss2_url");

  echo '<link rel="alternate" type="application/rss+xml" title="' .
    esc_attr(get_bloginfo("name")) .
    ' RSS Feed" href="' .
    esc_url($rss_feed_url) .
    '">' .
    "\n";
});
