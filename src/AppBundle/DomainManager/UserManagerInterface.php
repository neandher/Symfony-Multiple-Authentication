<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\User;

interface UserManagerInterface
{

    /**
     * @param User $user
     * @return void
     */
    public function editLastLogin(User $user);

    /**
     * @param string $email
     * @return User
     */
    public function findUserByEmail($email);

    /**
     * @param User $user
     * @return void
     */
    public function resettingRequest(User $user);

    /**
     * @param string $token
     * @return User
     */
    public function findUserByConfirmationToken($token);

    /**
     * @param User $user
     * @return void
     */
    public function resettingReset(User $user);

    /**
     * @param User $user
     * @return void
     */
    public function changePassword(User $user);

}