<?php

add_filter(
  "robots_txt",
  function ($output, $public) {
    if ($public) {
      $site_url = get_site_url();
      $output .= "Sitemap: {$site_url}/sitemap.xml\n";
    }
    return $output;
  },
  9999,
  2,
);
