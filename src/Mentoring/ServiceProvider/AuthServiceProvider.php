<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Controller\AuthController;
use Mentoring\Taxonomy\TermHydrator;
use Mentoring\User\UserHydrator;
use Mentoring\User\UserService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class AuthServiceProvider implements ServiceProviderInterface , ControllerProviderInterface
{
    public function boot(Application $app)
    {
        // Nothing happening at the moment
    }

    public function connect(Application $app)
    {
        /** @var \Silex\ControllerCollection; $controllers */
        $controllers = $app['controllers_factory'];
        $controllers
            ->get('/github', 'controller.auth:githubAction')
            ->bind('auth.github')
            ->bind('auth.login');

        $controllers
            ->get('/logout', 'controller.auth:logoutAction')
            ->bind('auth.logout');

        return $controllers;
    }

    public function register(Container $app)
    {
        $app['user.hydrator'] = function () use ($app) {
            return new UserHydrator($app['taxonomy.service'], new TermHydrator());
        };

        $app['user.manager'] = function () use ($app) {
            return new UserService($app['db'], $app['user.hydrator']);
        };

        $app['auth.mustAuthenticate'] = function (Application $app) {
            return function () use ($app) {
                if (!$app['session']->has('user')) {
                    return $app->redirect($app['url_generator']->generate('auth.login'));
                }
            };
        };

        $app['auth.isAdmin'] = function (Application $app) {
            return function () use ($app) {
                $user = $app['session']->get('user');
                if (!$user || $user->role != 'ROLE_ADMIN') {
                    $app['session']->getFlashBag()->add('error', 'You do not have privileges for the requested page');
                    return $app->redirect($app['url_generator']->generate('index'));
                }
            };
        };

        $app['controller.auth'] = function ($app) {
            return new AuthController();
        };
    }
}
