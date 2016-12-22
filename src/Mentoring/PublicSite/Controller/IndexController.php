<?php

namespace Mentoring\PublicSite\Controller;

use Mentoring\Conversation\Message;
use Mentoring\Form\ConversationStartForm;
use Mentoring\PublicSite\Blog\BlogService;
use Mentoring\User\UserNotFoundException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
    public function blogAction(Application $app)
    {
        $blogService = $app['service.blog'];
        $recentEntries = $blogService->fetchRecentEntries();

        return $app['twig']->render('index/blog/index.twig', ['entries' => $recentEntries]);
    }

    public function blogViewAction(Application $app, $slug)
    {
        /** @var BlogService $blogService */
        $blogService = $app['service.blog'];
        $post = $blogService->findEntry(['slug' => $slug, 'published' => 1]);

        return $app['twig']->render('index/blog/view.twig', ['post' => $post]);
    }

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
