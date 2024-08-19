<?php

namespace MunicipioExtended\Modularity\ModPosts\TemplateController;

use Modularity\Module\Posts\TemplateController\AbstractController;

/**
 * Class MixedTemplate
 *
 * Template controller for rendering posts as a mix of cards and list.
 *
 * @package Modularity\Module\Posts\TemplateController
 */
class MixedTemplate extends AbstractController {
  /**
   * MixedTemplate constructor.
   *
   * @param \Modularity\Module\Posts\Posts $module Instance of the Posts module.
   */
  public function __construct(\Modularity\Module\Posts\Posts $module) {
    parent::__construct($module);
  }

  public function addDataViewData(array $data, array $fields) {
    $data = parent::addDataViewData($data, $fields);
    $data["posts_columns"] = "";
    return $data;
  }
}
