<?php

namespace Seiffert\CrowdRestBundle\Tests;

use Seiffert\CrowdRestBundle\Crowd\User;
use Seiffert\CrowdRestBundle\Crowd\AuthResource;
use Seiffert\CrowdRestBundle\Crowd\UserResource;
use Seiffert\CrowdRestBundle\Crowd;

class CrowdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Crowd
     */
    private $crowd;

    /**
     * @var UserResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockUserResource;

    /**
     * @var AuthResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockAuthResource;

    public function setUp()
    {
        $this->crowd = new Crowd($this->getMockUserResource(), $this->getMockAuthResource());
    }

    public function testGetUser()
    {
        $username = 'tester';
        $user = new User();

        $this->getMockUserResource()
            ->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->will($this->returnValue($user));

        $this->assertSame($user, $this->crowd->getUser($username));
    }

    public function testIsAuthenticationValid()
    {
        $username = 'tester';
        $password = 'secret';

        $this->getMockAuthResource()
            ->expects($this->once())
            ->method('isAuthenticationValid')
            ->with($username, $password)
            ->will($this->returnValue(true));

        $this->assertTrue($this->crowd->isAuthenticationValid($username, $password));
    }

    /**
     * @return UserResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockUserResource()
    {
        if (null === $this->mockUserResource) {
            $this->mockUserResource = $this->getMock(
                'Seiffert\CrowdRestBundle\Crowd\UserResource',
                array(),
                array(),
                '',
                false
            );
        }

        return $this->mockUserResource;
    }

    /**
     * @return AuthResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockAuthResource()
    {
        if (null === $this->mockAuthResource) {
            $this->mockAuthResource = $this->getMock(
                'Seiffert\CrowdRestBundle\Crowd\AuthResource',
                array(),
                array(),
                '',
                false
            );
        }

        return $this->mockAuthResource;
    }
}
