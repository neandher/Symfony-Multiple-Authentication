<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;

abstract class AbstractManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function persistAndFlush($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}