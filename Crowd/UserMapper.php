<?php

namespace Seiffert\CrowdRestBundle\Crowd;

class UserMapper
{
    public function __invoke($user)
    {
        if ($user instanceof UserInterface) {
            return $this->mapUserToRestRepresentation($user);
        }
        return $this->mapRestRepresentationToUser($user);
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    private function mapUserToRestRepresentation(UserInterface $user)
    {
        return 'TODO';
    }

    /**
     * @param string $data
     * @return UserInterface $user
     */
    private function mapRestRepresentationToUser($data)
    {
        $user = new User();

        $user->setFirstName($data['first-name']);
        $user->setLastName($data['last-name']);
        $user->setDisplayName($data['display-name']);
        $user->setEmail($data['email']);
        $user->setActive($data['active']);
        $user->setUsername($data['name']);

        return $user;
    }
}
