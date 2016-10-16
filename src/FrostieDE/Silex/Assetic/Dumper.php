<?php

namespace FrostieDE\Silex\Assetic;

use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Extension\Twig\TwigResource;
use Assetic\Factory\LazyAssetManager;
use Symfony\Component\Finder\Finder;

class Dumper {
    private $assetManager;
    private $lazyAssetManager;
    private $writer;

    private $twigLoader;
    private $templatesDir;

    public function __construct($templatesDir, \Twig_LoaderInterface $loader, AssetWriter $writter, AssetManager $assetManager, LazyAssetManager $lazyAssetManager) {
        $this->assetManager = $assetManager;
        $this->lazyAssetManager = $lazyAssetManager;
        $this->writer = $writter;
        $this->twigLoader = $loader;
        $this->templatesDir = $templatesDir;
    }

    public function dump() {
        $templates = $this->findTemplatesInFolder($this->templatesDir);

        foreach($templates as $template) {
            $resource = new TwigResource($this->twigLoader, $template);
            $this->lazyAssetManager->addResource($resource, 'twig');
        }

        $this->writeManagerAssets($this->assetManager);
        $this->writeManagerAssets($this->lazyAssetManager);
    }

    private function writeManagerAssets(AssetManager $manager) {
        foreach($manager->getNames() as $name) {
            $asset = $manager->get($name);

            if($manager instanceof LazyAssetManager) {
                $formula = $manager->getFormula($name);
            }

            $this->writer->writeAsset($asset);

            if(!isset($formula[2])) {
                continue;
            }

            $debug   = isset($formula[2]['debug'])   ? $formula[2]['debug']   : $manager->isDebug();
            $combine = isset($formula[2]['combine']) ? $formula[2]['combine'] : null;
            if (null !== $combine ? !$combine : $debug) {
                foreach ($asset as $leaf) {
                    $this->writer->writeAsset($leaf);
                }
            }
        }
    }

    private function findTemplatesInFolder($folder) {
        $extension = '.html.twig';
        $finder = new Finder();

        $files = [ ];

        foreach($finder->files()->name('/' . $extension . '$/')->in($folder) as $file) {
            $files[] = $file->getRelativePathname();
        }

        return $files;
    }
}