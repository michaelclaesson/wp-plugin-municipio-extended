<?php

namespace MunicipioExtended\ComponentLibrary\Component;

use Kirki\Compatibility\Kirki;
use Municipio\Customizer;

class MxBaseController extends \ComponentLibrary\Component\BaseController {
  /**
   * The original BaseController creates filters based on the full class name,
   * so we need to remove the MunicipioExtended namespace to ensure it remains
   * the same.
   */
  public function createFilterName($class) {
    $name = parent::createFilterName($class);
    $name = preg_replace("/^MunicipioExtended\//", "", $name);
    return $name;
  }

  protected function getNamespaceParts() {
    //Get all parts of the location
    return explode("\\", get_called_class());
  }

  protected function getComponentName() {
    $namespaceParts = $this->getNameSpaceParts();
    return end($namespaceParts);
  }

  protected function getModifiers() {
    $componentName = $this->getComponentName();

    $modifiers = [];

    if (function_exists("apply_filters")) {
      //Applies a general wp filter
      $modifiers = apply_filters(
        "ComponentLibrary/Component/Modifier",
        $modifiers,
        $this->data["context"],
      );
      //Applies component specific wp filter
      $modifiers = apply_filters(
        "ComponentLibrary/Component/" . $componentName . "/Modifier",
        $modifiers,
        $this->data["context"],
      );
    }

    return $modifiers;
  }

  protected function getContexts() {
    return $this->data["context"] ?? [];
  }

  protected function hasModifier($modifier) {
    $modifiers = $this->getModifiers();
    return in_array($modifier, $modifiers);
  }

  protected function hasContext($context) {
    $contexts = $this->getContexts();
    return in_array($context, $contexts);
  }

  protected function getKirkiOption($name) {
    $value = Kirki::get_option(Customizer::KIRKI_CONFIG, $name);
    return $value;
  }
}
