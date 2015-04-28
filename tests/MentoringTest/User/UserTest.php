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
            'enabled' => true,
            'isMentee' => true,
            'isMentor' => false,
        ];

        $user = new User();
        $user->setEmail($testData['email']);
        $user->setGithubUid($testData['githubUid']);
        $user->setId($testData['id']);
        $user->setName($testData['name']);
        $user->setRoles($testData['roles']);
        $user->setTimeCreated($testData['timeCreated']);
        $user->setIsEnabled($testData['enabled']);
        $user->setIsMentee($testData['isMentee']);
        $user->setIsMentor($testData['isMentor']);

        $this->assertEquals($testData['email'], $user->getEmail());
        $this->assertEquals($testData['githubUid'], $user->getGithubUid());
        $this->assertEquals($testData['id'], $user->getId());
        $this->assertEquals($testData['name'], $user->getName());
        $this->assertEquals($testData['roles'], $user->getRoles());
        $this->assertEquals($testData['timeCreated'], $user->getTimeCreated());
        $this->assertEquals($testData['enabled'], $user->isEnabled());
        $this->assertEquals($testData['isMentee'], $user->isMentee());
        $this->assertEquals($testData['isMentor'], $user->isMentor());
    }
}