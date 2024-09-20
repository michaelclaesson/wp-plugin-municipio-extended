<?php

add_filter("municipio/remove_script_versions", "__return_false");

add_filter("Municipio/Template/viewData", function ($data) {
  $data["mainContentBottomMargin"] = 0;
  return $data;
});
