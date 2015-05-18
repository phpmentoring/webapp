<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Controller\AccountController;
use Mentoring\Controller\ConversationController;
use Mentoring\Controller\IndexController;
use Mentoring\Conversation\ConversationRepository;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class ConversationServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function connect(Application $app)
    {
        /** @var \Silex\ControllerCollection; $controllers */
        $controllers = $app['controllers_factory'];
        $controllers
            ->match('/', 'controller.conversation:indexAction')
            ->method('GET')
            ->bind('conversation.index')
            ->before($app['auth.mustAuthenticate']);

        $controllers
            ->match('/', 'controller.conversation:createAction')
            ->method('POST')
            ->bind('conversation.create')
            ->before($app['auth.mustAuthenticate']);

        $controllers
            ->match('/{conversation_id}', 'controller.conversation:viewAction')
            ->method('GET|POST')
            ->bind('conversation.view')
            ->before($app['auth.mustAuthenticate']);

        return $controllers;
    }

    public function register(Application $app)
    {
        $app['conversation_repository'] = $app->share(
            function (Application $app) {
                return new ConversationRepository($app['db'], $app['user.manager']);
            }
        );

        $app['controller.conversation'] = $app->share(
            function (Application $app) {
                return new ConversationController();
            }
        );
    }
}
