<?php

namespace Mentoring\Mailer;

use Mentoring\Conversation\Conversation;use Mentoring\Conversation\Message;use Mentoring\User\User;use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer
{
    protected $mailer;
    protected $twig;
    protected $urlGenerator;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailer = $mailer;
        $this->twig   = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function sendNotificationForNewMessage(Conversation $conversation, Message $message)
    {
        $send_to = $conversation->withUser($message->getFromUser());

        if (!$send_to->hasSendNotifications()) {
            // dont send if the user unchecked the "notify" setting on the profile form
            return;
        }

        $this->sendEmail(
            $send_to,
            'You recieved a new message',
            'email/new-message.twig',
            [
                'message_from' => $message->getFromUser()->getName(),
                'message_link' => $this->urlGenerator->generate(
                    'conversation.view',
                    ['conversation_id' => $conversation->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
    }

    public function sendNotificationForNewConversation(Conversation $conversation)
    {
        // this method leaves us the option to send a different email if its the start of a conversation
        $this->sendNotificationForNewMessage($conversation, $conversation->getFirstMessage());
    }

    protected function sendEmail(User $to, $subject, $template, $vars)
    {
        $body = $this->twig->render($template, array_merge([
            'to_name' => $to->getName(),
            'to_email' => $to->getEmail()
        ], $vars));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->addTo($to->getEmail())
            ->addFrom($this->getDefaultFromEmail())
            ->setBody($body)
        ;

        $this->mailer->send($message);
    }

    public function getDefaultFromEmail()
    {
        return 'no-reply@phpmentoring.org';
    }
}
