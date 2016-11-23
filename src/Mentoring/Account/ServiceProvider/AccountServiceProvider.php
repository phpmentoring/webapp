<?php

namespace Mentoring\Account\ServiceProvider;

use Mentoring\Account\Controller\AccountController;
use Mentoring\Account\Form\ProfileForm;
use Mentoring\Account\Command\AddMissingGithubUsernamesCommand;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

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

    public function register(Container $app)
    {
        $app['console']->add(new AddMissingGithubUsernamesCommand());

        $app['controller.account'] = function ($app) {
            return new AccountController();
        };

        $app['account.type.profile'] = function ($app) {
            return new ProfileForm($app['taxonomy.service']);
        };

        $app->extend('form.types', function ($types) use ($app) {
            $types[] = 'account.type.profile';

            return $types;
        });
    }
}
