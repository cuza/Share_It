<?php

namespace BrightSoft\StorageBundle\DataFixtures\ORM;

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
        return 20;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $file_manager = $manager->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager'));
        $user = $manager->getRepository('UserBundle:Role')->findOneBy(array('name' => 'user'));

        $task = new Task();
        $task->setName('Manage Categories')
            ->setIcon('icon-th-list')
            ->setRoute('category')
            ->setRole($file_manager);
        $manager->persist($task);

        $task = new Task();
        $task->setName('Manage File Types')
            ->setIcon('icon-file')
            ->setRoute('filetype')
            ->setRole($file_manager);
        $manager->persist($task);

        $task = new Task();
        $task->setName('My Files')
            ->setIcon('icon-file')
            ->setRoute('storage')
            ->setRole($user);
        $manager->persist($task);

        $manager->flush();
    }
}
