<?php

namespace FrostieDE\Silex;

use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\LazyAssetManager;
use Assetic\FilterManager;
use FrostieDE\Silex\Assetic\Dumper;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

class AsseticServiceProvider implements ServiceProviderInterface, BootableProviderInterface {

    /**
     * @inheritDoc
     */
    public function register(Container $app) {
        $app['assetic.options'] = [
            'assets_path' => '',
            'web_path' => ''
        ];

        $app['assetic.manager'] = function() {
            return new AssetManager();
        };

        $app['assetic.filter_manager'] = function() {
            return new FilterManager();
        };

        $app['assetic.factory'] = function($app) {
            $factory = new AssetFactory($app['assetic.options']['assets_path'], $app['debug']);
            $factory->setAssetManager($app['assetic.manager']);
            $factory->setFilterManager($app['assetic.filter_manager']);

            return $factory;
        };

        $app['assetic.writer'] = function($app) {
            return new AssetWriter($app['assetic.options']['web_path']);
        };

        $app['assetic.lazy_asset_manager'] = function($app) {
            $manager = new LazyAssetManager($app['assetic.factory']);
            $manager->setLoader('twig', new TwigFormulaLoader($app['twig']));

            return $manager;
        };

        $app['assetic.dumper'] = function($app) {
            return new Dumper($app['twig.path'], $app['twig.loader'], $app['assetic.writer'], $app['assetic.manager'], $app['assetic.lazy_asset_manager']);
        };

        $app->extend('twig', function(\Twig_Environment $twig) use($app) {
            $twig->addExtension(new AsseticExtension($app['assetic.factory']));

            return $twig;
        });
    }

    /**
     * @inheritDoc
     */
    public function boot(Application $app) {
        if($app['debug']) {
            // only auto-dump when using debug
            $app->after(function () use ($app) {
                $app['assetic.dumper']->dump();
            });
        }
    }

}