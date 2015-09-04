<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class UserLoader extends DataFixtureLoader implements OrderedFixtureInterface
{
    protected function getFixtures()
    {
        return array(
            __DIR__ . '/user.yml',
            __DIR__ . '/gestorUser.yml',
            __DIR__ . '/adminUser.yml',
        );
    }

    public function roles()
    {
        $roles = array(
            'ROLE_APP',
            'ROLE_PORTAL',
        );

        return array($roles[array_rand($roles)]);
    }

    public function myPasswords()
    {
        $passwords = array(
            '1234',
            '4321',
        );

        return $passwords[array_rand($passwords)];
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

}