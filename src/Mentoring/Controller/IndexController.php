<?php

namespace Mentoring\Controller;

use Silex\Application;

class IndexController
{
    public function indexAction(Application $app)
    {
        return $app['twig']->render('index/index.twig');
    }

    public function mentorsAction(Application $app)
    {
        return $app['twig']->render('index/mentors.twig');
    }

    public function apprenticesAction(Application $app)
    {
        return $app['twig']->render('index/apprentices.twig');
    }
}
