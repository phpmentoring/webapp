<?php

namespace Mentoring\Taxonomy\ServiceProvider;

use Mentoring\Taxonomy\TaxonomyService;
use Mentoring\Taxonomy\TermHydrator;
use Mentoring\Taxonomy\VocabularyHydrator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class TaxonomyServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    public function boot(Application $app)
    {
        // No services currently need registering
    }

    public function connect(Application $app)
    {
        // No controllers currently being registered
    }

    public function register(Container $app)
    {
        $app['taxonomy.service'] = function ($app) {
            return new TaxonomyService($app['db'], new VocabularyHydrator(), new TermHydrator());
        };
    }
}
