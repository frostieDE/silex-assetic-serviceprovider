<?php

namespace FrostieDE\Silex\Assetic;

use Assetic\Asset\FileAsset;
use Assetic\AssetManager;
use Symfony\Component\Finder\Finder;

class StaticAssetsHelper {
    private static $counter = 1;

    public static function copyDirectory($directory, AssetManager $manager, $webPath = '') {
        $finder = new Finder();
        $directoryName = basename($directory);

        foreach($finder->files()->in($directory) as $file) {
            $asset = new FileAsset($file->getRealPath());
            $asset->setTargetPath(sprintf('%s/%s/%s', $webPath, $directoryName, $file->getRelativePathname()));
            $manager->set(sprintf('static%d', static::$counter), $asset);
            static::$counter++;
        }
    }
}