<?php

namespace FrostieDE\Silex\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use MatthiasMullie\Minify\Minify;

/**
 * Enables enabling a filter only if a given regular expression is evaluated against
 * the filename.
 *
 * This can be used to only run minification if the is not already minified (e.g. *.min.css).
 */
abstract class AbstractMinifyFilter implements FilterInterface {

    protected $filterRegex = null;

    /**
     * @return string
     */
    abstract protected function minify($path);

    /**
     * @inheritDoc
     */
    public function filterLoad(AssetInterface $asset) {
    }

    /**
     * @inheritDoc
     */
    public function filterDump(AssetInterface $asset) {
        if($this->canMinify($asset) === false) {
            $asset->setContent($asset->getContent());
            return;
        }

        $path = $asset->getSourceRoot() . '/' . $asset->getSourcePath();
        $asset->setContent($this->minify($path));
    }

    protected final function canMinify(AssetInterface $asset) {
        $path = $asset->getSourcePath();

        if($this->filterRegex === null) {
            return true;
        }

        if(preg_match($this->filterRegex, $path)) {
            return true;
        }

        return false;
    }
}