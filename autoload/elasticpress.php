<?php

/**
 * Keep ElasticPress index settings in the site-specific MU plugin layer
 * instead of the active theme, so search configuration survives theme changes.
 */
add_filter("ep_default_index_number_of_shards", function ($shards) {
  return (int) apply_filters(
    "mx_elasticpress_default_index_number_of_shards",
    1,
    $shards,
  );
});

add_filter("ep_default_index_number_of_replicas", function ($replicas) {
  return (int) apply_filters(
    "mx_elasticpress_default_index_number_of_replicas",
    0,
    $replicas,
  );
});
