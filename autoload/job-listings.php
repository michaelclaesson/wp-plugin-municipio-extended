<?php

add_filter(
  "Municipio/viewData",
  function ($data) {
    if (
      $data["isSingular"] &&
      ($data["post"]->postType ?? "") == "job-listing"
    ) {
      $articleContentBefore = $data["hook"]->articleContentBefore;
      $articleContentBefore .= '<div class="tailwind">';
      $articleContentBefore .= mx_render_view(
        "mxui.job-listing.article-content-before",
        $data,
      );
      $articleContentBefore .= "</div>";
      $data["hook"]->articleContentBefore = $articleContentBefore;
    }
    return $data;
  },
  20,
);
