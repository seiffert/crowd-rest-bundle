<?php

namespace Seiffert\CrowdRestBundle\Exception;

class UserNotFoundException extends \Exception
{
    public function __construct($username)
    {
        parent::__construct('Could not find user ' . $username . '.');
    }
}
