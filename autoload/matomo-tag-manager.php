<?php

add_action("acf/init", function () {
  // Add subpage for Tracking options
  acf_add_options_sub_page([
    "page_title" => _x("Tracking", "Options Page Title", "municipio-extended"),
    "menu_title" => _x(
      "Tracking",
      "Options Page Menu Title",
      "municipio-extended",
    ),
    "parent_slug" => "options-general.php",
    "menu_slug" => "acf-options-mx-tracking",
    "capability" => "manage_options",
    "autoload" => true,
  ]);

  acf_add_local_field_group([
    "key" => "group_mx_matomo",
    "title" => __("Matomo", "municipio-extended"),
    "fields" => [
      [
        "key" => "field_mx_matomo_url",
        "label" => __("URL", "municipio-extended"),
        "name" => "mx_matomo_url",
        "type" => "text",
        "instructions" => __(
          "The URL for the Matomo Tag Manager.",
          "municipio-extended",
        ),
        "constant" => "MATOMO_URL",
      ],
      [
        "key" => "field_mx_matomo_container_id",
        "label" => __("Container ID", "municipio-extended"),
        "name" => "mx_matomo_container_id",
        "type" => "text",
        // "instructions" => __(
        //   "The container ID for the Matomo Tag Manager.",
        //   "municipio-extended",
        // ),
        "constant" => "MATOMO_CONTAINER_ID",
      ],
      [
        "key" => "field_mx_matomo_site_id",
        "label" => __("Site ID", "municipio-extended"),
        "name" => "mx_matomo_site_id",
        "type" => "text",
        "instructions" => __(
          "The site ID for the Matomo Tag Manager.",
          "municipio-extended",
        ),
        "constant" => "MATOMO_SITE_ID",
      ],
    ],
    "location" => [
      [
        [
          "param" => "options_page",
          "operator" => "==",
          "value" => "acf-options-mx-tracking",
        ],
      ],
    ],
  ]);
});

add_filter("acf/prepare_field", function ($field) {
  if (
    $field["constant"] &&
    defined($field["constant"]) &&
    constant($field["constant"])
  ) {
    $field["value"] = constant($field["constant"]);
    $field["disabled"] = true;
    $field["instructions"] =
      (empty($field["instructions"]) ? "" : $field["instructions"] . "\n") .
      "<i>" .
      sprintf(
        __(
          "This field is disabled because the constant %s has been defined in code.",
          "municipio-extended",
        ),
        "<code>{$field["constant"]}</code>",
      ) .
      "</i>";
  }
  return $field;
});

function mx_matomo_option_is_contant($option) {
  $constant = "MATOMO_" . strtoupper($option);
  return defined($constant) && constant($constant);
}

function mx_get_matomo_option($option) {
  $constant = "MATOMO_" . strtoupper($option);
  if (defined($constant) && constant($constant)) {
    return constant($constant);
  }
  return get_field("mx_matomo_" . $option, "option");
}

add_action("wp_head", function () {
  $url = mx_get_matomo_option("url");
  $container_id = mx_get_matomo_option("container_id");
  $site_id = mx_get_matomo_option("site_id");
  if ($container_id && $url): ?>
      <!-- Matomo Tag Manager -->
      <script>
        var _mtm = window._mtm = window._mtm || [];
        _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
        (function() {
          var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
          g.async=true;
          g.src='<?php echo $url; ?>js/container_' + '<?php echo $container_id; ?>' + '.js';
          s.parentNode.insertBefore(g,s);
        })();
      </script>
      <!-- End Matomo Tag Manager -->
      <?php elseif (!$container_id && $url && $site_id): ?>
        <!-- Matomo -->
        <script>
          var _paq = window._paq = window._paq || [];
          _paq.push(['requireCookieConsent']);
          _paq.push(['trackPageView']);
          _paq.push(['enableLinkTracking']);
          (function() {
            var u="<?php echo $url; ?>";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '<?php echo $site_id; ?>']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
          })();
        </script>
        <!-- End Matomo Code -->
        <?php endif;
});
