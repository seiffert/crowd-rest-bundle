<?php

namespace Seiffert\CrowdRestBundle\Tests\Crowd;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Seiffert\CrowdRestBundle\Crowd\AuthResource;

/**
 * @covers Seiffert\CrowdRestBundle\Crowd\AuthResource
 */
class AuthResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthResource
     */
    private $resource;

    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    public function setUp()
    {
        $this->resource = new AuthResource($this->getMockHttpClient());
    }

    public function testIsAuthenticationValidSuccess()
    {
        $username = 'test-user';
        $password = 'test-passwort';

        $this->getMockHttpClient()
            ->expects($this->once())
            ->method('post')
            ->with('authentication?username=' . $username, null, json_encode(array('value' => $password)))
            ->will($this->returnValue($this->createMockRequest()));

        $this->assertTrue($this->resource->isAuthenticationValid($username, $password));
    }

    public function testIsAuthenticationValidInvalidCredentials()
    {
        $that = $this;

        $request = $this->createMockRequest(
            function ($request) use ($that) {
                return $that->throwException(
                    ClientErrorResponseException::factory($request, $that->createClientErrorResponse(400))
                );
            }
        );

        $this->getMockHttpClient()->expects($this->once())->method('post')->will($this->returnValue($request));

        $this->assertFalse($this->resource->isAuthenticationValid('foo', 'bar'));
    }

    public function testIsAuthenticationValidServerError()
    {
        $that = $this;

        $request = $this->createMockRequest(
            function ($request) use ($that) {
                return $that->throwException(
                    BadResponseException::factory($request, $that->createMockResponse())
                );
            }
        );

        $this->getMockHttpClient()->expects($this->once())->method('post')->will($this->returnValue($request));

        $this->setExpectedException('Guzzle\Http\Exception\BadResponseException');
        $this->assertFalse($this->resource->isAuthenticationValid('foo', 'bar'));
    }

    /**
     * @return Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = $this->getMock('Guzzle\Http\Client');
        }

        return $this->httpClient;
    }

    /**
     * @param callable $willAtSend
     * @return Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createMockRequest($willAtSend = null)
    {
        $request = $this->getMock('Guzzle\Http\Message\Request', array(), array(), '', false);

        if (is_callable($willAtSend)) {
            $request->expects($this->once())
                ->method('send')
                ->will($willAtSend($request));
        }

        return $request;
    }

    /**
     * @return Response|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createMockResponse()
    {
        return $this->getmock('Guzzle\Http\Message\Response', array(), array(), '', false);
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
