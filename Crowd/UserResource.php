<?php

namespace Seiffert\CrowdRestBundle\Crowd;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Seiffert\CrowdRestBundle\Exception\UserNotFoundException;
use Seiffert\CrowdRestBundle\Crowd\UserMapper;

class UserResource
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var UserMapper
     */
    private $userMapper;

    /**
     * @param Client $httpClient
     * @param UserMapper $userMapper
     */
    public function __construct(Client $httpClient, UserMapper $userMapper)
    {
        $this->httpClient = $httpClient;
        $this->userMapper = $userMapper;
    }

    /**
     * @param string $username
     *
     * @throws UserNotFoundException
     *
     * @return UserInterface
     */
    public function getUserByUsername($username)
    {
        $request = $this->httpClient->get('user?username=' . urlencode($username) . '&expand=attributes');

        try {
            $response = $request->send();
        } catch (ClientErrorResponseException $exception) {
            if (404 === $exception->getResponse()->getStatusCode()) {
                throw new UserNotFoundException($exception->getResponse()->getBody());
            }
            throw $exception;
        }

        $userMapper = $this->userMapper;
        return $userMapper($response->json());
    }
}
