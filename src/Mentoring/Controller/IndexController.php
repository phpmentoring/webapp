<?php

namespace Mentoring\Controller;

use Silex\Application;

class IndexController
{
    public function indexAction(Application $app)
    {
        return $app['twig']->render('index/index.twig');
    }
}
