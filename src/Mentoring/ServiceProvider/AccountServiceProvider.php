<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Controller\AccountController;
use Mentoring\Controller\IndexController;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Mentoring\User\CountryService;

class AccountServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
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
            ->match('/', 'controller.account:profileAction')
            ->method('GET|POST')
            ->bind('account.profile')
            ->before($app['auth.mustAuthenticate']);

        return $controllers;
    }

    public function register(Application $app)
    {
        $app['controller.account'] = $app->share(
            function ($app) {
                return new AccountController();
            }
        );

        $app['country.manager'] = function () use ($app) {
            return new CountryService($app['db']);
        };
    }
}
