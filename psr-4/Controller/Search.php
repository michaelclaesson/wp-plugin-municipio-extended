<?php

namespace MunicipioExtended\Controller;

class Search extends \Municipio\Controller\Archive {
  public function init() {
    parent::init();
    $this->data["gridColumnClass"] = explode(
      " ",
      $this->data["gridColumnClass"],
    );
  }

  /**
   * We don't need any posts for the search page.
   */
  public function getPosts($template): array {
    return [];
  }
}
