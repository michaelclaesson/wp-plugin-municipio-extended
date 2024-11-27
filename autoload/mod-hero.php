<?php

/**
 * Doubles the resolution of the hero image
 */
add_filter("Modularity/Module/Hero/imageSize", function ($size) {
  $width = $size[0];
  $width = $width * 2;
  $size[0] = $width;
  return $size;
});
