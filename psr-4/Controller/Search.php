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
}
