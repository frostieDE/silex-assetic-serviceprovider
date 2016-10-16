<?php

namespace FrostieDE\Silex\Assetic\Filter;

use MatthiasMullie\Minify\CSS;

class CssMinifyFilter extends AbstractMinifyFilter {

    public function __construct() {
        $this->filterRegex = '~(?<!\.min)\.css$~';
    }

    /**
     * @inheritDoc
     */
    protected function minify($path) {
        $css = new CSS();
        $css->setImportExtensions([]); // do not import any css
        $css->add($path);

        return $css->minify();
    }
}