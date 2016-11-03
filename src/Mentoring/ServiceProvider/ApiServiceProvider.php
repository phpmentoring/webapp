<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Controller\ApiController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class ApiServiceProvider implements ServiceProviderInterface , ControllerProviderInterface
{
    public function boot(Application $app)
    {
        // No services currently need registering
    }

    public function connect(Application $app)
    {
        /** @var \Silex\ControllerCollection; $controllers */
        $controllers = $app['controllers_factory'];
        $controllers
            ->match('/mentors', 'controller.api:getMentorsAction')
            ->method('GET')
            ->bind('api.get.mentors')
        ;

        $controllers
            ->match('/apprentices', 'controller.api:getApprenticesAction')
            ->method('GET')
            ->bind('api.get.apprentices')
        ;

        $controllers
            ->match('/vocabulary/{vocabularyName}/term', 'controller.api:getTerms')
            ->method('GET')
            ->bind('api.get.terms')
        ;

        $controllers
            ->match('/to-markdown', 'controller.api:toMarkdown')
            ->method('POST')
            ->bind('api.post.to_markdown')
        ;

        return $controllers;
    }

    public function register(Container $app)
    {
        $app['controller.api'] = function ($app) {
            return new ApiController();
        };
    }
}
