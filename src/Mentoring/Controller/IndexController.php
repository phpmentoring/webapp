<?php

namespace Mentoring\Controller;

use Mentoring\Conversation\Message;
use Mentoring\Form\ConversationStartForm;
use Mentoring\User\UserNotFoundException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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

    public function apprenticesAction(Application $app)
    {
        return $app['twig']->render('index/apprentices.twig');
    }

    public function whyAction(Application $app)
    {
        return $app['twig']->render('index/why.twig');
    }

    public function viewProfileAction(Application $app, Request $request, $user_id)
    {
        if (!$viewing_user = $app['user.manager']->fetchUserById($user_id)) {
            throw new UserNotFoundException;
        }

        $form = null;
        if ($user = $app['session']->get('user')) {
            $form = $app['form.factory']->create('conversation.type.conversation_start', ['to' => $user_id], [
                'action' => $app['url_generator']->generate('conversation.create')
            ]);
        }


        return $app['twig']->render('index/profile.twig', [
            'viewing_user' => $viewing_user,
            'message_form' => $form === null ? null : $form->createView(),
            'viewing_yourself' => ($user && $viewing_user->getId() == $user->getId())
        ]);
    }
}
