<?php

namespace BrightSoft\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BrightSoft\UserBundle\Entity\Task;

class Tasks extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 5;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $admin = $manager->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));

        $task = new Task();
        $task->setName('Manage Users')
            ->setIcon('icon-user')
            ->setRoute('user')
            ->setRole($admin);
        $manager->persist($task);

        $task = new Task();
        $task->setName('Manage Tasks')
            ->setIcon('icon-cog')
            ->setRoute('task')
            ->setRole($admin);
        $manager->persist($task);

        $task = new Task();
        $task->setName('Manage Roles')
            ->setIcon('icon-briefcase')
            ->setRoute('role')
            ->setRole($admin);
        $manager->persist($task);

        $manager->flush();
    }
}
