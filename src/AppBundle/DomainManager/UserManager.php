<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;

class UserManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\UserRepository
     */
    public $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository('AppBundle:User');
    }
}