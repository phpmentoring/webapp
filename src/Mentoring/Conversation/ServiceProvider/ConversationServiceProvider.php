<?php

namespace Mentoring\Conversation\ServiceProvider;

use Mentoring\Conversation\Controller\ConversationController;
use Mentoring\Conversation\ConversationRepository;
use Mentoring\Conversation\ConversationTwigExtension;
use Mentoring\Conversation\MarkdownConverter;
use Mentoring\Conversation\Form\ConversationReplyForm;
use Mentoring\Conversation\Form\ConversationStartForm;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

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

    public function register(Container $app)
    {
        $app['conversation_repository'] = function (Application $app) {
            return new ConversationRepository($app['db'], $app['user.manager']);
        };

        $app['controller.conversation'] = function (Application $app) {
            return new ConversationController();
        };

        $app['conversation.markdown_converter'] = function (Application $app) {
            return new MarkdownConverter();
        };

        $app['conversation.type.conversation_start'] = function ($app) {
            return new ConversationStartForm();
        };

        $app['conversation.type.conversation_reply'] = function ($app) {
            return new ConversationReplyForm();
        };

        $app->extend('form.types', function ($types) use ($app) {
            $types[] = 'conversation.type.conversation_start';
            $types[] = 'conversation.type.conversation_reply';

            return $types;
        });

        $app['twig']->addExtension(
            new ConversationTwigExtension(
                $app['conversation_repository'],
                $app['conversation.markdown_converter']
            )
        );
    }
}
