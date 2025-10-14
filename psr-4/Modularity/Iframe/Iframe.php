<?php

namespace MunicipioExtended\Modularity\Iframe;

class Iframe extends \Modularity\Module\Iframe\Iframe {
  public function data(): array {
    $data["url"] = get_field("iframe_url", $this->ID);
    $data["height"] = get_field("iframe_height", $this->ID);
    $data["description"] = get_field("iframe_description", $this->ID);

    return $data;
  }
}
