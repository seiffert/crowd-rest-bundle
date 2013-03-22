<?php

namespace Seiffert\CrowdRestBundle\Crowd;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

class AuthResource
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @throws ClientErrorResponseException
     *
     * @return bool
     */
    public function isAuthenticationValid($username, $password)
    {
        $request = $this->httpClient->post(
            'authentication?username=' . urlencode($username),
            null,
            $this->createAuthenticationRequestBody($password));

        try {
            $request->send();
        } catch (ClientErrorResponseException $exception) {
            if (400 === $exception->getResponse()->getStatusCode()) {
                return false;
            }
            throw $exception;
        }

        return true;
    }

    /**
     * @param string $password
     * @return string
     */
    private function createAuthenticationRequestBody($password)
    {
        return json_encode(array('value' => $password));
    }
}
