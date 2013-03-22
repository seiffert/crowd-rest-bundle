<?php

namespace Seiffert\CrowdRestBundle\Tests\Crowd;

use Seiffert\CrowdRestBundle\Crowd\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testGetSetUsername()
    {
        $username = 'tester';

        $this->user->setUsername($username);

        $this->assertSame($username, $this->user->getUsername());
    }

    public function testGetSetFirstName()
    {
        $firstName = 'test';

        $this->user->setFirstName($firstName);

        $this->assertSame($firstName, $this->user->getFirstName());
    }

    public function testGetSetLastName()
    {
        $lastName = 'tester';

        $this->user->setLastName($lastName);

        $this->assertSame($lastName, $this->user->getLastName());
    }

    public function testGetSetDisplayName()
    {
        $displayName = 'test tester';

        $this->user->setDisplayName($displayName);

        $this->assertSame($displayName, $this->user->getDisplayName());
    }

    /**
     * @param bool $flag
     * @dataProvider trueFalseProvider
     */
    public function testIsSetActive($active)
    {
        $this->user->setActive($active);

        $this->assertSame($active, $this->user->isActive());
    }

    public function testGetSetEmail()
    {
        $email = 'test@example.com';

        $this->user->setEmail($email);

        $this->assertSame($email, $this->user->getEmail());
    }

    /**
     * @return array|bool[][]
     */
    public function trueFalseProvider()
    {
        return array(array(true), array(false));
    }
}
