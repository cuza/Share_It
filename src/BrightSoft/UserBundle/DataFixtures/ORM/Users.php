<?php

namespace BrightSoft\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BrightSoft\UserBundle\Entity\User;

class Users extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 3;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('admin')
            ->setEmail('admin@shareit.now.im')
            ->setPass('admin')
            ->addRole($manager->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin')));
        $manager->persist($user);

        $manager->flush();
    }
}
