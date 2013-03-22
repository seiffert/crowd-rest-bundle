<?php

namespace Seiffert\CrowdRestBundle\Tests\Crowd;

use Seiffert\CrowdRestBundle\Crowd\UserMapper;

class UserMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserMapper
     */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new UserMapper();
    }

    public function testRestRepresentationToUser()
    {
        $m = $this->mapper;

        $data = array(
            'first-name' => 'test',
            'last-name' => 'tester',
            'display-name' => 'test tester',
            'name' => 'test',
            'active' => true,
            'email' => 'test@example.com'
        );
        $user = $m($data);

        $this->assertEquals($user->getFirstName(), $data['first-name']);
        $this->assertEquals($user->getLastName(), $data['last-name']);
        $this->assertEquals($user->getDisplayName(), $data['display-name']);
        $this->assertEquals($user->getUsername(), $data['name']);
        $this->assertEquals($user->getEmail(), $data['email']);
        $this->assertEquals($user->isActive(), $data['active']);
    }
}
