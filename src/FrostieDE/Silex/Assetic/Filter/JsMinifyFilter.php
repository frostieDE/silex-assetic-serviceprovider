<?php

namespace FrostieDE\Silex\Assetic\Filter;

use Patchwork\JSqueeze;

class JsMinifyFilter extends AbstractMinifyFilter {

    public function __construct() {
        $this->filterRegex = '~(?<!\.min)\.js$~';
    }

    /**
     * @inheritDoc
     */
    protected function minify($path) {
        $jz = new JSqueeze();

        return $jz->squeeze(file_get_contents($path), true, false, false);
    }
}