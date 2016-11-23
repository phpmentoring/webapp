<?php

namespace Mentoring\PublicSite\ServiceProvider;

use Mentoring\PublicSite\Blog\BlogService;
use Mentoring\PublicSite\Command\ImportBlogEntries;
use Mentoring\PublicSite\Controller\IndexController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class IndexServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
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
            ->get('/', 'controller.index:indexAction')
            ->bind('index');

        $controllers
            ->get('/guidelines', 'controller.index:guidelinesAction')
            ->bind('guidelines');

        $controllers
            ->get('/mentors', 'controller.index:mentorsAction')
            ->bind('mentors');

        $controllers
            ->get('/apprentices', 'controller.index:apprenticesAction')
            ->bind('apprentices');

        $controllers
            ->get('/profile/{user_id}', 'controller.index:viewProfileAction')
            ->bind('profile');

        $controllers
            ->get('/why', 'controller.index:whyAction')
            ->bind('why');

        return $controllers;
    }

    public function register(Container $app)
    {
        $app['service.blog'] = function($app) {
            return new BlogService($app['db']);
        };

        $app['command.publicsite.import_blog_entries'] = function($app) {
            new ImportBlogEntries($app['service.blog'], $app['publicsite.blog_directory']);
        };

        $app['console']->add($app['command.publicsite.import_blog_entries']);

        $app['controller.index'] = function ($app) {
            return new IndexController();
        };
    }
}
