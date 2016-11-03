<?php

namespace Mentoring\ServiceProvider;

use Mentoring\Mailer\Mailer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class MailerServiceProvider implements ServiceProviderInterface , ControllerProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function connect(Application $app)
    {
    }

    public function register(Container $container)
    {
        $container['mentoring_mailer'] = function ($container) {
            return new Mailer($container['mailer'], $container['twig'], $container['url_generator']);
        };
    }
}
