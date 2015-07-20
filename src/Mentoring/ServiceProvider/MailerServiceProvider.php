<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Controller\AccountController;
use Mentoring\Controller\ConversationController;
use Mentoring\Controller\IndexController;
use Mentoring\Conversation\ConversationRepository;
use Mentoring\Conversation\ConversationTwigExtension;
use Mentoring\Conversation\MarkdownConverter;
use Mentoring\Mailer\Mailer;use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class MailerServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function connect(Application $app)
    {
    }

    public function register(Application $app)
    {
        $app['mentoring_mailer'] = $app->share(
            function (Application $app) {
                return new Mailer($app['mailer'], $app['twig'], $app['url_generator']);
            }
        );
    }
}
