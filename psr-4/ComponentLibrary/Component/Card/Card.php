<?php

namespace MunicipioExtended\ComponentLibrary\Component\Card;

use Illuminate\Support\HtmlString;
use Kirki;
use Municipio\Customizer;
use MunicipioExtended\ComponentLibrary\Component\MxBaseController;

class Card extends MxBaseController {
  public function originalInit() {
    extract($this->data);

    $this->data["collpaseID"] = uniqid();

    $this->data["classList"][] = $this->getBaseClass() . "--" . $color;

    $this->data["afterContentSlotHasData"] = $this->slotHasData("afterContent");

    $this->data["floatingSlotHasData"] = $this->slotHasData("floating");

    if (isset($image["padded"]) && $image["padded"]) {
      $this->data["paddedImage"] =
        $this->getBaseClass() . "__image-background--padded";
    }

    if ($image && !empty($image["src"])) {
      $this->data["classList"][] = $this->getBaseClass("has-image", true);
    }

    if ($dateBadge && $date) {
      $this->data["classList"][] = $this->getBaseClass("has-datebadge", true);
    }

    if ($imageFirst || !$image) {
      $this->data["classList"][] = $this->getBaseClass() . "--image-first";
    }

    if ($hasFooter || $tags || $buttons) {
      $this->data["classList"][] = $this->getBaseClass() . "--has-footer";
    }

    if ($metaFirst) {
      $this->data["classList"][] = $this->getBaseClass() . "--meta-first";
    }

    if ($collapsible && $content) {
      $this->data["collapsible"] = $this->getBaseClass() . "--collapse";
    }

    if (!empty($image) && is_string($image)) {
      $image = $this->data["image"] = [
        "src" => $image,
      ];
    }

    if (!empty($icon)) {
      $this->data["icon"]["classList"][] = $this->getBaseClass("icon");
    }

    if (!isset($this->data["displayIcon"])) {
      $this->data["displayIcon"] = true;
    }

    if (!empty($hasPlaceholder)) {
      $this->data["classList"][] = $this->getBaseClass() . "--svg-background";
    }

    if (
      ($image && !isset($image["src"])) ||
      (isset($image["src"]) && empty($image["src"]))
    ) {
      $this->data["image"] = false;
    }

    if (is_array($image) && !isset($image["backgroundColor"])) {
      $this->data["image"]["backgroundColor"] = "primary";
    }

    if ($link) {
      $this->data["componentElement"] = "a";
      $this->data["attributeList"]["href"] = $link;
    } else {
      $this->data["componentElement"] = "div";
    }

    if ($link) {
      $this->data["classList"][] = $this->getBaseClass() . "--action";
    }

    if ($ratio) {
      $this->data["classList"][] =
        $this->getBaseClass() . "--ratio-" . str_replace(":", "-", $ratio);
    }
  }

  protected function getKirkiOption($name) {
    $value = Kirki::get_option(Customizer::KIRKI_CONFIG, $name);
    return $value;
  }

  public function init() {
    $this->data["useHbg"] =
      $this->data["useHbg"] ?? !$this->getKirkiOption("card_mxui_enabled");
    if ($this->data["useHbg"]) {
      return $this->originalInit();
    }

    /**
     * The rest of this method transforms original data structure into MXUI data
     * structure
     */

    extract($this->data);

    $this->data["modifiers"] = $this->getModifiers();
    $this->data["modifier"] = reset($this->data["modifiers"]);

    $this->data["content"] = $content ?? null ?: $slot ?? null;

    if ($this->hasContext("module.manual-input.card")) {
      $this->data["content"] =
        $this->data["content"] && is_string($this->data["content"])
          ? new HtmlString($this->data["content"])
          : $this->data["content"];
      $this->data["wrapContent"] = true;
    }
  }
}
