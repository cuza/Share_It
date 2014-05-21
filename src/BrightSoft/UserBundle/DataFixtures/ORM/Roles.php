<?php

namespace BrightSoft\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BrightSoft\UserBundle\Entity\Role;

class Roles extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('user')
            ->setDescription('Standard User');
        $manager->persist($role);

        $role = new Role();
        $role->setName('premium')
            ->setDescription('Premium User');
        $manager->persist($role);

        $role = new Role();
        $role->setName('admin')
            ->setDescription('Administrator');
        $manager->persist($role);

        $manager->flush();
    }
}
