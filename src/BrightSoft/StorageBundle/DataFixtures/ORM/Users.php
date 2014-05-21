<?php

namespace BrightSoft\StorageBundle\DataFixtures\ORM;

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
        return 17;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('file_manager')
            ->setEmail('file_manager@shareit.now.im')
            ->setPass('file_manager')
            ->addRole($manager->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager')));
        $manager->persist($user);

        $manager->flush();
    }
}
