<?php

namespace MentoringTest\Conversation;

use Mentoring\Conversation\Conversation;
use Mentoring\Conversation\Message;

class ConversationTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAndGetters()
    {
        $fromUser = \Mockery::mock('\Mentoring\User\User');
        $toUser = \Mockery::mock('\Mentoring\User\User');
        $subject = 'My subject';
        $opening_message = \Mockery::mock('\Mentoring\Conversation\Message');
        $opening_message->shouldReceive('getCreatedAt')->andReturn($started_at = new \DateTime());

        $conversation = new Conversation($fromUser, $toUser, $subject, $opening_message);

        $this->assertSame($fromUser, $conversation->getFromUser());
        $this->assertSame($toUser, $conversation->getToUser());
        $this->assertSame($subject, $conversation->getSubject());
        $this->assertSame($started_at, $conversation->getStartedAt());
        $this->assertSame($opening_message, $conversation->getFirstMessage());
    }

    public function testStartNew()
    {
        $fromUser = \Mockery::mock('Mentoring\User\User');
        $toUser = \Mockery::mock('Mentoring\User\User');
        $subject = 'My subject';
        $opening_message = 'My opening message';

        $conversation = Conversation::startNew($fromUser, $toUser, $subject, $opening_message);

        $this->assertSame($fromUser, $conversation->getFromUser());
        $this->assertSame($toUser, $conversation->getToUser());
        $this->assertSame($subject, $conversation->getSubject());
        $this->assertSame($subject, $conversation->getSubject());
        $this->assertInstanceOf('\DateTime', $conversation->getStartedAt());
        $this->assertInstanceOf('\Mentoring\Conversation\Message', $conversation->getFirstMessage());
        $this->assertSame($opening_message, $conversation->getFirstMessage()->getBody());
    }

    public function testAddingAndGettingReplies()
    {
        $conversation = $this->createSimpleConversation();

        $m1 = new Message(\Mockery::mock('Mentoring\User\User'), 'my message', new \DateTime());
        $m2 = new Message(\Mockery::mock('Mentoring\User\User'), 'my message', new \DateTime());

        $conversation->addMessage($m1);
        $conversation->addMessage($m2);

        $this->assertEquals(
            array($m1, $m2),
            $conversation->getReplies()
        );
    }

    public function testGettingAMessageById()
    {
        $conversation = $this->createSimpleConversation();

        $m1 = new Message(\Mockery::mock('Mentoring\User\User'), 'my message', new \DateTime());
        $m2 = new Message(\Mockery::mock('Mentoring\User\User'), 'my message', new \DateTime());

        $conversation->addMessage($m1);
        $conversation->addMessage($m2);

        $m1->setId(10);
        $m2->setId(56);
        $conversation->getFirstMessage()->setId(5);

        $this->assertEquals($m1, $conversation->getMessageById(10));
        $this->assertEquals($m2, $conversation->getMessageById(56));
        $this->assertEquals($conversation->getFirstMessage(), $conversation->getMessageById(5));
    }

    public function testGetAllMessages()
    {
        $conversation = $this->createSimpleConversation();

        $m1 = new Message(\Mockery::mock('Mentoring\User\User'), 'my message', new \DateTime());
        $m2 = new Message(\Mockery::mock('Mentoring\User\User'), 'my message', new \DateTime());

        $conversation->addMessage($m1);
        $conversation->addMessage($m2);

        $this->assertCount(3, $conversation->getAllMessages());
    }

    public function testInvolvesUser()
    {
        $fromUser = \Mockery::mock('Mentoring\User\User');
        $toUser = \Mockery::mock('Mentoring\User\User');
        $subject = 'My subject';
        $opening_message = 'My opening message';

        $conversation = Conversation::startNew($fromUser, $toUser, $subject, $opening_message);

        $notInvolved = \Mockery::mock('Mentoring\User\User');

        $fromUser->shouldReceive('getId')->andReturn(1);
        $toUser->shouldReceive('getId')->andReturn(2);
        $notInvolved->shouldReceive('getId')->andReturn(3);

        $this->assertTrue($conversation->involvesUser($fromUser));
        $this->assertTrue($conversation->involvesUser($toUser));
        $this->assertFalse($conversation->involvesUser($notInvolved));
    }

    public function testWith()
    {
        $fromUser = \Mockery::mock('Mentoring\User\User');
        $toUser = \Mockery::mock('Mentoring\User\User');
        $subject = 'My subject';
        $opening_message = 'My opening message';

        $conversation = Conversation::startNew($fromUser, $toUser, $subject, $opening_message);

        $fromUser->shouldReceive('getId')->andReturn(1);
        $toUser->shouldReceive('getId')->andReturn(2);

        $this->assertSame($fromUser, $conversation->withUser($toUser));
        $this->assertSame($toUser, $conversation->withUser($fromUser));
    }

    public function testIdCanBeSet()
    {
        $conversation = $this->createSimpleConversation();

        $conversation->setId(1);
        $this->assertSame(1, $conversation->getId());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testIdCanOnlyBeSetOnce()
    {
        $conversation = $this->createSimpleConversation();

        $conversation->setId(1);
        $conversation->setId(1);
    }

    /**
     * @return Conversation
     */
    protected function createSimpleConversation()
    {
        $conversation = Conversation::startNew(
            \Mockery::mock('Mentoring\User\User'),
            \Mockery::mock('Mentoring\User\User'),
            'My subject',
            'My opening message'
        );

        return $conversation;
    }
}
