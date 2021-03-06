<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{

    public function findGestorUserByEmail($emailCononical)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.gestorUser','g')
            ->andWhere('u.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCononical)
            ->andWhere('u.roles like :role')->setParameter(':role', '%ROLE_GESTOR_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findGestorUserByConfirmationToken($token)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.gestorUser','g')
            ->andWhere('u.confirmationToken = :token')->setParameter('token', $token)
            ->andWhere('u.roles like :role')->setParameter(':role', '%ROLE_GESTOR_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAdminUserByEmail($emailCononical)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.adminUser','a')
            ->andWhere('u.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCononical)
            ->andWhere('u.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAdminUserByConfirmationToken($token)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.adminUser','a')
            ->andWhere('u.confirmationToken = :token')->setParameter('token', $token)
            ->andWhere('u.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
