<?php

namespace Mentoring\Controller;

use Silex\Application;

class IndexController
{
    public function guidelinesAction(Application $app)
    {
        return $app['twig']->render('index/guidelines.twig');
    }

    public function indexAction(Application $app)
    {
        return $app['twig']->render('index/index.twig');
    }

    public function mentorsAction(Application $app)
    {
        return $app['twig']->render('index/mentors.twig');
    }

    public function menteesAction(Application $app)
    {
        return $app['twig']->render('index/mentees.twig');
    }

    public function whyAction(Application $app)
    {
        return $app['twig']->render('index/why.twig');
    }
}
