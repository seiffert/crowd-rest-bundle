<?php

namespace Seiffert\CrowdRestBundle;

use Seiffert\CrowdRestBundle\Crowd\AuthResource;
use Seiffert\CrowdRestBundle\Crowd\UserInterface;
use Seiffert\CrowdRestBundle\Crowd\UserResource;
use Seiffert\CrowdRestBundle\Exception\UserNotFoundException;

class Crowd
{
    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * @var AuthResource
     */
    private $authResource;

    /**
     * @param UserResource $userResource
     * @param AuthResource $authResource
     */
    public function __construct(UserResource $userResource, AuthResource $authResource)
    {
        $this->userResource = $userResource;
        $this->authResource = $authResource;
    }

    /**
     * @param string $username
     *
     * @throws UserNotFoundException
     * @return UserInterface
     */
    public function getUser($username)
    {
        return $this->userResource->getUserByUsername($username);
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function isAuthenticationValid($username, $password)
    {
        return $this->authResource->isAuthenticationValid($username, $password);
    }
}
