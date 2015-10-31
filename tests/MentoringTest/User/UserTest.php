<?php

namespace MentoringTest\User;

use Mentoring\User\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Makes sure that the setters and getters work properly
     *
     * @author Chris Tankersley <chris@ctankersley.com>
     * @since 2015-04-28
     */
    public function testSettersAndGetters()
    {
        $testData = [
            'email' => 'test@test.com',
            'githubUid' => '1234',
            'id' => 1,
            'name' => 'Mr. McTest',
            'roles' => ['ROLE_USER'],
            'timeCreated' => new \DateTime(),
            'timezone' => new \DateTimeZone("Europe/London"),
            'enabled' => true,
            'isMentee' => true,
            'isMentor' => false,
            'sendNotifications' => true,
            'profile' => 'I am interested in learning about PHP.'
        ];

        $user = new User();
        $user->setEmail($testData['email']);
        $user->setGithubUid($testData['githubUid']);
        $user->setId($testData['id']);
        $user->setName($testData['name']);
        $user->setRoles($testData['roles']);
        $user->setTimeCreated($testData['timeCreated']);
        $user->setTimezone($testData['timezone']);
        $user->setIsEnabled($testData['enabled']);
        $user->setIsMentee($testData['isMentee']);
        $user->setIsMentor($testData['isMentor']);
        $user->setSendNotifications($testData['sendNotifications']);
        $user->setProfile($testData['profile']);

        $this->assertEquals($testData['email'], $user->getEmail());
        $this->assertEquals($testData['githubUid'], $user->getGithubUid());
        $this->assertEquals($testData['id'], $user->getId());
        $this->assertEquals($testData['name'], $user->getName());
        $this->assertEquals($testData['roles'], $user->getRoles());
        $this->assertEquals($testData['timeCreated'], $user->getTimeCreated());
        $this->assertEquals($testData['enabled'], $user->isEnabled());
        $this->assertEquals($testData['isMentee'], $user->isMentee());
        $this->assertEquals($testData['isMentor'], $user->isMentor());
        $this->assertEquals($testData['sendNotifications'], $user->hasSendNotifications());
        $this->assertEquals($testData['profile'], $user->getProfile());
        $this->assertEquals($testData['timezone'], $user->getTimezone());
    }
}