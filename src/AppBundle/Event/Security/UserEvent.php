<?php

namespace AppBundle\Event\Security;

use AppBundle\DomainManager\UserManagerInterface;
use AppBundle\Entity\SecureAreaInterface;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{

    const PARAM_RESETTING_EMAIL_ROUTE = 'security.resetting_email.route';
    const PARAM_RESETTING_EMAIL_FROM = 'security.resetting_email.from';
    const PARAM_RESETTING_EMAIL_TEMPLATE = 'security.resetting_email.template';

    /**
     * @var User
     */
    private $user;

    /**
     * @var SecureAreaInterface
     */
    private $secureArea;

    /**
     * @var UserManagerInterface
     */
    private $manager;

    /**
     * @var bool
     */
    private $hasError;

    /**
     * @var array
     */
    private $params;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param User $user
     *
     * @return UserEvent
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     *
     * @return UserEvent
     */
    public function setParams(Array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return SecureAreaInterface
     */
    public function getSecureArea()
    {
        return $this->secureArea;
    }

    /**
     * @param SecureAreaInterface $secureArea
     *
     * @return UserEvent
     */
    public function setSecureArea(SecureAreaInterface $secureArea)
    {
        $this->secureArea = $secureArea;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHasError()
    {
        return $this->hasError;
    }

    /**
     * @param bool $hasError
     *
     * @return UserEvent
     */
    public function setHasError($hasError)
    {
        $this->hasError = $hasError;

        return $this;
    }

    /**
     * @return UserManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param UserManagerInterface $manager
     *
     * @return UserEvent
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

}