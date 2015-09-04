<?php

namespace AppBundle\DomainManager\Admin\Acesso;

use AppBundle\DomainManager\AbstractManager;
use AppBundle\DomainManager\UserManager;
use AppBundle\DomainManager\UserManagerInterface;
use AppBundle\Entity\Admin\Acesso\AdminUser;
use AppBundle\Entity\User;
use AppBundle\Event\Security\UserEvent;
use AppBundle\Event\Security\UserEvents;
use AppBundle\Helper\CanonicalizerHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManager;

class AdminUserManager extends AbstractManager implements UserManagerInterface
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var CanonicalizerHelper
     */
    private $canonicalizer;

    /**
     * @var \AppBundle\Repository\Admin\Acesso\AdminUserRepository
     */
    private $repository;

    public function __construct(
        EntityManager $entityManager,
        UserManager $userManager,
        EventDispatcherInterface $eventDispatcher,
        CanonicalizerHelper $canonicalizer
    ) {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->canonicalizer = $canonicalizer;

        $this->repository = $this->entityManager->getRepository('AppBundle:Admin\Acesso\AdminUser');

        parent::__construct($this->entityManager);
    }

    public function editLastLogin(User $user)
    {
        self::persistAndFlush($user);
    }


    public function findUserByEmail($email)
    {
        return $this->userManager->repository->findAdminUserByEmail($this->canonicalizer->canonicalize($email));
    }

    public function resettingRequest(User $user)
    {
        self::persistAndFlush($user);

        $userEvent = new UserEvent($user);
        $userEvent
            ->setSecureArea($user->getAdminUser())
            ->setParams(
                array(
                    $userEvent::PARAM_RESETTING_EMAIL_ROUTE    => 'admin_security_resetting_reset',
                    $userEvent::PARAM_RESETTING_EMAIL_FROM     => 'security.resetting_email.from',
                    $userEvent::PARAM_RESETTING_EMAIL_TEMPLATE => 'admin/security/resetting/email.html.twig'
                )
            );

        $this->eventDispatcher->dispatch(UserEvents::RESETTING_REQUEST_SUCCESS, $userEvent);
    }

    public function findUserByConfirmationToken($token)
    {
        return $this->userManager->repository->findAdminUserByConfirmationToken($token);
    }

    public function resettingReset(User $user)
    {
        $this->eventDispatcher->dispatch(UserEvents::RESETTING_RESET_SUCCESS, new UserEvent($user));

        self::persistAndFlush($user);
    }

    public function changePassword(User $user)
    {
        self::persistAndFlush($user);
    }

    public function create(AdminUser $adminUser)
    {
        self::persistAndFlush($adminUser);
    }

    public function checkUniqueEmail($email)
    {
        return $this->repository->checkUniqueEmail($this->canonicalizer->canonicalize($email));
    }
}