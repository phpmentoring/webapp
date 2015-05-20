<?php

namespace MentoringTest\Conversation;

use Mentoring\Conversation\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAndGetters()
    {
        $fromUser = \Mockery::mock('\Mentoring\User\User');
        $body = 'This is my message';
        $createdAt = new \DateTime();

        $message = new Message($fromUser, $body, $createdAt);

        $this->assertSame($fromUser, $message->getFromUser());
        $this->assertSame($body, $message->getBody());
    }

    public function testStartsAsUnreadAndCanBeMarkedAsRead()
    {
        $fromUser = \Mockery::mock('\Mentoring\User\User');
        $body = 'This is my message';
        $createdAt = new \DateTime();

        $message = new Message($fromUser, $body, $createdAt);

        $this->assertFalse($message->isRead());
        $message->markRead();
        $this->assertTrue($message->isRead());
    }

    public function testIdCanBeSet()
    {
        $conversation = $this->createSimpleMessage();

        $conversation->setId(1);
        $this->assertSame(1, $conversation->getId());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testIdCanOnlyBeSetOnce()
    {
        $conversation = $this->createSimpleMessage();

        $conversation->setId(1);
        $conversation->setId(1);
    }

    protected function createSimpleMessage()
    {
        return new Message(\Mockery::mock('\Mentoring\User\User'), 'this is my message', new \DateTime());
    }
}
