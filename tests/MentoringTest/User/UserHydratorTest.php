<?php

namespace MentoringTest\User;

use Mentoring\User\User;
use Mentoring\User\UserHydrator;

class UserHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Returns a usable set of test data
     *
     * @return array
     */
    protected function getTestData()
    {
        return [
            'email' => 'test@test.com',
            'githubUid' => '1234',
            'id' => 1,
            'name' => 'Mr. McTest',
            'roles' => ['ROLE_USER'],
            'timeCreated' => new \DateTime(),
            'isEnabled' => true,
            'isMentee' => true,
            'isMentor' => false,
            'profile' => 'my profile information.'
        ];
    }

    protected function getTestUser()
    {
        $testData = $this->getTestData();

        $user = new User();
        $user->setEmail($testData['email']);
        $user->setGithubUid($testData['githubUid']);
        $user->setId($testData['id']);
        $user->setName($testData['name']);
        $user->setRoles($testData['roles']);
        $user->setTimeCreated($testData['timeCreated']);
        $user->setIsEnabled($testData['isEnabled']);
        $user->setIsMentee($testData['isMentee']);
        $user->setIsMentor($testData['isMentor']);
        $user->setProfile($testData['profile']);

        return $user;
    }

    /**
     * Makes sure that hydrator properly hydrates
     *
     * @author Chris Tankersley <chris@ctankersley.com>
     * @since 2015-04-28
     */
    public function testUserIsProperlyHydrated()
    {
        $testData = $this->getTestData();

        $user = new User();
        $hydrator = new UserHydrator();
        $user = $hydrator->hydrate($testData, $user);

        $this->assertEquals($testData['email'], $user->getEmail());
        $this->assertEquals($testData['githubUid'], $user->getGithubUid());
        $this->assertEquals($testData['id'], $user->getId());
        $this->assertEquals($testData['name'], $user->getName());
        $this->assertEquals($testData['roles'], $user->getRoles());
        $this->assertEquals($testData['timeCreated'], $user->getTimeCreated());
        $this->assertEquals($testData['isEnabled'], $user->isEnabled());
        $this->assertEquals($testData['isMentee'], $user->isMentee());
        $this->assertEquals($testData['isMentor'], $user->isMentor());
        $this->assertEquals($testData['profile'], $user->getProfile());
    }

    /**
     * Makes sure that we handle string dates properly during hydration
     *
     * @author Chris Tankersley <chris@ctankersley.com>
     * @since 2015-04-28
     */
    public function testTimeCreatedHyrdatesWhenNotDateTime()
    {
        $testData = $this->getTestData();
        $testData['timeCreated'] = $testData['timeCreated']->format(\DateTime::ISO8601);

        $user = new User();
        $hydrator = new UserHydrator();
        $user = $hydrator->hydrate($testData, $user);

        $this->assertEquals($testData['timeCreated'], $user->getTimeCreated()->format(\DateTime::ISO8601));
    }

    /**
     * Makes sure that a serialized array for Roles is properly turned into a real array
     *
     * @author Chris Tankersley <chris@ctankersley.com>
     * @since 2015-04-28
     */
    public function testRolesAreUnserializedIfString()
    {
        $testData = $this->getTestData();
        $roles = $testData['roles'];
        $testData['roles'] = serialize($testData['roles']);

        $user = new User();
        $hydrator = new UserHydrator();
        $user = $hydrator->hydrate($testData, $user);

        $this->assertEquals($roles, $user->getRoles());
    }

    /**
     * Makes sure that a user object is properly turned into an array
     *
     * @author Chris Tankersley <chris@ctankersley.com>
     * @since 2015-04-28
     */
    public function testExtractionWorks()
    {
        $user = $this->getTestUser();
        $hydrator = new UserHydrator();
        $data = $hydrator->extract($user);

        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertEquals($data['githubUid'], $user->getGithubUid());
        $this->assertEquals($data['id'], $user->getId());
        $this->assertEquals($data['name'], $user->getName());
        $this->assertEquals($data['roles'], $user->getRoles());
        $this->assertEquals($data['timeCreated'], $user->getTimeCreated()->format(\DateTime::ISO8601));
        $this->assertEquals($data['isEnabled'], $user->isEnabled());
        $this->assertEquals($data['isMentee'], $user->isMentee());
        $this->assertEquals($data['isMentor'], $user->isMentor());
        $this->assertEquals($data['profile'], $user->getProfile());
    }
}