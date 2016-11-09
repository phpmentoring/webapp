<?php

namespace Mentoring\Controller;

use Mentoring\Conversation\Conversation;
use Mentoring\Conversation\Message;
use Mentoring\User\UserNotFoundException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Form\FormInterface;

class ConversationController
{
    public function indexAction(Application $app, Request $request)
    {
        $user = $app['session']->get('user');

        $my_conversations = $app['conversation_repository']->findAllInvolvingUser($user);

        return $app['twig']->render('conversation/index.twig', array(
            'conversations' => $my_conversations,
            'user' => $user
        ));
    }

    public function viewAction(Application $app, Request $request, $conversation_id)
    {
        $user = $app['session']->get('user');

        /** @var \Mentoring\Conversation\ConversationRepository $conversationRepo */
        $conversationRepo = $app['conversation_repository'];
        $conversation = $conversationRepo->findById($conversation_id);

        if (!$conversation->involvesUser($user)) {
            throw new AccessDeniedHttpException('You do not have access to conversations you are not involved in');
        }

        // user has read
        $conversation->markUserHasRead($user);
        $conversationRepo->save($conversation);

        $form = $app['form.factory']->create('conversation.type.conversation_reply');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $form_data = $form->getData();
            $message = new Message($user, $form_data['body'], new \DateTime());
            $conversation->addMessage($message);
            $conversationRepo->save($conversation);

            $app['mentoring_mailer']->sendNotificationForNewMessage($conversation, $message);

            return $app->redirect(
                $app['url_generator']->generate('conversation.view', ['conversation_id' => $conversation->getId()])
            );
        }

        return $app['twig']->render('conversation/view.twig', array(
            'conversation' => $conversation,
            'user' => $user,
            'message_form' => $form->createView()
        ));
    }

    public function createAction(Application $app, Request $request)
    {
        $user = $app['session']->get('user');

        /** @var FormInterface $form **/
        $form = $app['form.factory']->create('conversation.type.conversation_start');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $form_data = $form->getData();
            if (!$to_user = $app['user.manager']->fetchUserById($form_data['to'])) {
                throw new UserNotFoundException;
            }
            $conversation = Conversation::startNew($user, $to_user, $form_data['subject'], $form_data['body']);
            $app['conversation_repository']->save($conversation);

            $app['mentoring_mailer']->sendNotificationForNewConversation($conversation);

            return $app->redirect(
                $app['url_generator']->generate('conversation.view', ['conversation_id' => $conversation->getId()])
            );
        }

        // TODO: make a view that allows you to see / edit errors
        $app['session']->getFlashBag()->add(
            'danger',
            'A problem occurred when trying to send your message. Please try again.'
        );

        return $app->redirect($app['url_generator']->generate('conversation.index'));
    }
}
