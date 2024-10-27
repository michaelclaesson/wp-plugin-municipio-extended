<?php
add_action('wp_head', 'inject_matomo_script');

function inject_matomo_script() {
    if (!defined('MATOMO_CONTAINER_ID')) {
        return;
    }

    ?>
    <!-- Matomo Tag Manager -->
    <script>
      var _mtm = window._mtm = window._mtm || [];
      _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
      (function() {
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true;
        g.src='https://premium.analys.cloud/js/container_' + '<?php echo MATOMO_CONTAINER_ID; ?>' + '.js';
        s.parentNode.insertBefore(g,s);
      })();
    </script>
    <!-- End Matomo Tag Manager -->
    <?php
}
