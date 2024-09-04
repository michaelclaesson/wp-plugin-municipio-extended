<?php

namespace MunicipioExtended\ComponentLibrary\Component\Datebadge;

use Illuminate\Support\HtmlString;
use MunicipioExtended\ComponentLibrary\Component\MxBaseController;

class Datebadge extends MxBaseController {
  public function originalInit() {
    extract($this->data);

    //Sizes
    if (in_array($size, ["sm", "md"])) {
      $this->data["classList"][] = $this->getBaseClass() . "--" . $size;
    } else {
      $this->data["classList"][] = $this->getBaseClass() . "--md";
    }

    //Format
    $date = strtotime($date);
    $this->data["month"] = $this->getDateFunc("M", $date);
    $this->data["day"] = $this->getDateFunc("j", $date);
    $this->data["time"] = $this->getDateFunc("H:i", $date);
  }

  /**
   * Copied from the original component, for use in originalInit
   */
  private function getDateFunc($format, $date) {
    if (function_exists("wp_date")) {
      return wp_date($format, $date);
    }
    return date($format, $date);
  }

  public function init() {
    $this->data["useHbg"] = $this->data["useHbg"] ?? true;
    // $this->data["useHbg"] =
    //   $this->data["useHbg"] ?? !$this->getKirkiOption("datebadge_mxui_enabled");
    if ($this->data["useHbg"]) {
      return $this->originalInit();
    }

    /**
     * The rest of this method transforms original data structure into MXUI data
     * structure
     */

    extract($this->data);

    $this->data["classList"] = array_diff($this->data["classList"], [
      "u-height--100",
    ]);

    $this->data["date"] = mx_date($date);
  }
}
