<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Controller\AuthController;
use Mentoring\User\UserHydrator;
use Mentoring\User\UserService;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
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
            ->bind('auth.login')
        ;

        $controllers
            ->get('/logout', 'controller.auth:logoutAction')
            ->bind('auth.logout')
        ;

        return $controllers;
    }

    public function register(Application $app)
    {
        $app['user.manager'] = function() use($app) {
            return new UserService($app['db'], new UserHydrator());
        };

        $app['auth.mustAuthenticate'] = function(Request $request) use ($app) {
            $request->getSession()->start();
            if(!$app['session']->has('user')) {
                return $app->redirect('/auth/login');
            }
        };

        $app['auth.isAdmin'] = function() use ($app) {
            $user = $app['session']->get('user');
            if(!$user || $user->role != 'ROLE_ADMIN') {
                $app['session']->getFlashBag()->add('error', 'You do not have privileges for the requested page');
                return $app->redirect('/');
            }
        };

        $app['controller.auth'] = $app->share(function($app) {
            return new AuthController();
        });
    }


}