<?php

namespace Mentoring\PublicSite\ServiceProvider;

use Mentoring\PublicSite\Blog\BlogEntryHydrator;
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

        $controllers
            ->get('/blog', 'controller.index:blogAction')
            ->bind('blog_home');

        $controllers
            ->get('/blog/{slug}', 'controller.index:blogViewAction')
            ->bind('blog_view');

        return $controllers;
    }

    public function register(Container $app)
    {
        $app['publicsite.blog_entry.hydrator'] = function() {
            return new BlogEntryHydrator();
        };

        $app['service.blog'] = function($app) {
            return new BlogService($app['db'], $app['publicsite.blog_entry.hydrator']);
        };

        $blogPath = realpath($app['basedir'] . '/' . $app['config']['app']['blog_directory']) . '/';
        $app['console']->add(new ImportBlogEntries($app['service.blog'], $blogPath));

        $app['controller.index'] = function ($app) {
            return new IndexController();
        };
    }
}
