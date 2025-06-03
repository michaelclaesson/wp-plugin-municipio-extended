<?php

add_filter(
  "robots_txt",
  function ($output, $public) {
    $output = "";
    $output .= "User-agent: *\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Allow: /wp-admin/admin-ajax.php\n";

    $upload_dir = wp_get_upload_dir();
    if ($upload_dir["baseurl"] ?? null) {
      $upload_url = str_replace(home_url(), "", $upload_dir["baseurl"]);
      $output .= "Disallow: {$upload_url}/\n";
    }

    $site_url = get_site_url();
    $output .= "\n";
    $output .= "Sitemap: {$site_url}/sitemap.xml\n";

    if (!$public) {
      $output .= "\n";
      $output .= "Disallow: /\n";
    }
    return $output;
  },
  9999,
  2,
);
