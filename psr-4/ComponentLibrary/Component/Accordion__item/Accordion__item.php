<?php

namespace MunicipioExtended\ComponentLibrary\Component\Accordion__item;

/**
 * Class Accordion
 * @package ComponentLibrary\Component\Accordion
 */
class Accordion__item extends
  \ComponentLibrary\Component\Accordion__item\Accordion__item {
  public function init() {
    parent::init();

    /**
     * Makes sure content is formatted when wrapped in a Tailwind context
     */
    $this->data["beforeContent"] = '<div class="prose">';
    $this->data["afterContent"] = "</div>";
  }
}
