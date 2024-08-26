<?php

/**
 * Fix for network sites where the attachment URL is incorrect.
 */
add_action("wp_get_attachment_url", function ($url) {
  $site_url = rtrim(home_url(), "/");
  $network_url = rtrim(network_home_url(), "/");
  $url = str_replace($network_url . "/", $site_url . "/", $url);
  return $url;
});
