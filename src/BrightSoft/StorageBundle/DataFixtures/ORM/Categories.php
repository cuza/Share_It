<?php

namespace BrightSoft\StorageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BrightSoft\StorageBundle\Entity\Category;

class Categories extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 10;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $cat = new Category();
        $cat->setName('Files')
        ->setDescription('All the files')
        ->setIcon('file');
        $manager->persist($cat);

        $cat = new Category();
        $cat->setName('Videos')
            ->setDescription('Video files')
            ->setIcon('video');
        $manager->persist($cat);

        $cat = new Category();
        $cat->setName('Music')
            ->setDescription('Music files')
            ->setIcon('music');
        $manager->persist($cat);

        $cat = new Category();
        $cat->setName('Photos')
            ->setDescription('Photo files')
            ->setIcon('photo');
        $manager->persist($cat);

        $cat = new Category();
        $cat->setName('Books')
            ->setDescription('Book files')
            ->setIcon('book');
        $manager->persist($cat);

        $cat = new Category();
        $cat->setName('Apps')
            ->setDescription('Game files')
            ->setIcon('games');
        $manager->persist($cat);

        $cat = new Category();
        $cat->setName('Archives')
            ->setDescription('Archive files')
            ->setIcon('archives');
        $manager->persist($cat);

        $manager->flush();
    }
}
