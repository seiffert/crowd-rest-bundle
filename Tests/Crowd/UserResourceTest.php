<?php

namespace Seiffert\CrowdRestBundle\Tests\Crowd;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Seiffert\CrowdRestBundle\Crowd\User;
use Seiffert\CrowdRestBundle\Crowd\UserMapper;
use Seiffert\CrowdRestBundle\Crowd\UserResource;

class UserResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserResource
     */
    private $resource;

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHttpClient;

    /**
     * @var UserMapper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockUserMapper;

    public function setUp()
    {
        $this->resource = new UserResource($this->getMockHttpClient(), $this->getMockUserMapper());
    }

    public function testGetUserByUsernameSuccess()
    {
        $json = array('foo' => 'bar');
        $username = 'test';
        $user = new User();
        $that = $this;

        $this->getMockHttpClient()
            ->expects($this->once())
            ->method('get')
            ->with('user?username=' . $username . '&expand=attributes')
            ->will($this->returnValue($this->createMockRequest(
                function () use ($that, $json) {
                    return $that->returnValue($that->createMockResponse($json));
                }
            )));
        $this->getMockUserMapper()->expects($this->once())
            ->method('__invoke')
            ->with($json)
            ->will($this->returnValue($user));

        $this->assertSame($user, $this->resource->getUserByUsername($username));
    }

    public function testGetUserByUsernameNotFound()
    {
        $that = $this;

        $this->getMockHttpClient()
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->createMockRequest(
                function ($request) use ($that) {
                    return $that->throwException(
                        ClientErrorResponseException::factory($request, $that->createClientErrorResponse(404))
                    );
                }
            )));

        $this->setExpectedException('Seiffert\CrowdRestBundle\Exception\UserNotFoundException');
        $this->resource->getUserByUsername('test');
    }

    public function testGetUserByUsernameServerError()
    {
        $that = $this;

        $this->getMockHttpClient()
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->createMockRequest(
                function ($request) use ($that) {
                    return $that->throwException(
                        ServerErrorResponseException::factory($request, $that->createMockResponse())
                    );
                }
            )));

        $this->setExpectedException('Guzzle\Http\Exception\BadResponseException');
        $this->resource->getUserByUsername('test');
    }

    /**
     * @return Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockHttpClient()
    {
        if (null === $this->mockHttpClient) {
            $this->mockHttpClient = $this->getMock('Guzzle\Http\Client');
        }

        return $this->mockHttpClient;
    }

    /**
     * @return UserMapper|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockUserMapper()
    {
        if (null === $this->mockUserMapper) {
            $this->mockUserMapper = $this->getMock(
                'Seiffert\CrowdRestBundle\Crowd\UserMapper',
                array(),
                array(),
                '',
                false
            );
        }

        return $this->mockUserMapper;
    }

    /**
     * @param array $json
     * @return Response|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createMockResponse($json = null)
    {
        $response = $this->getMock('Guzzle\Http\Message\Response', array(), array(), '', false);

        if (null !== $json) {
            $response->expects($this->once())
                ->method('json')
                ->will($this->returnValue($json));
        }

        return $response;
    }

    /**
     * @param callable $sendWill
     * @return Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createMockRequest($sendWill = null)
    {
        $request = $this->getMock('Guzzle\Http\Message\Request', array(), array(), '', false);

        if (is_callable($sendWill)) {
            $request->expects($this->once())
                ->method('send')
                ->will($sendWill($request));
        }

        return $request;
    }

    /**
     * @param int $status
     * @return Response|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createClientErrorResponse($status)
    {
        $response = $this->createMockResponse();

        $response->expects($this->any())
            ->method('isClientError')
            ->will($this->returnValue(true));

        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($status));

        return $response;
    }
}
