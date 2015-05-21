<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Taxonomy\TaxonomyService;
use Mentoring\Taxonomy\TermHydrator;
use Mentoring\Taxonomy\VocabularyHydrator;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

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

    public function register(Application $app)
    {
        $app['taxonomy.service'] = $app->share(
            function ($app) {
                return new TaxonomyService($app['db'], new VocabularyHydrator(), new TermHydrator());
            }
        );
    }
}
