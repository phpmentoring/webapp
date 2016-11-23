<?php

namespace Mentoring\Account\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AccountController
{
    public function profileAction(Application $app, Request $request)
    {
        $user = $app['session']->get('user');
        $form = $app['form.factory']->create('account.type.profile', $user);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $app['user.manager']->saveUser($user);
                $app['session']->getFlashBag()->add('success', 'Your profile has been saved.');
                $app['session']->set('user', $user);
                return $app->redirect($app['url_generator']->generate('account.profile'));
            }
        }

        return $app['twig']->render('account/profile.twig', array(
            'profile_form' => $form->createView(),
            'user' => $user
        ));
    }
}
