<?php

namespace BrightSoft\StorageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BrightSoft\StorageBundle\Entity\FileType;

class FileTypes extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 11;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $cat = $manager->getRepository('StorageBundle:Category')->findOneBy(array('name' => 'Files'));
        $path = __DIR__ . "/../../../../../web/ico";
        $d = dir($path);
        while (false !== ($entry = $d->read()))
            if ($entry != '.' && $entry != '..') {
                $entry = preg_split('@\.@', $entry)[0];
                $ft = new FileType();
                $ft->setName($entry)
                    ->setIcon($entry)
                    ->setDescription('')
                    ->setCategory($cat);
                $manager->persist($ft);
            }
        $d->close();
        $manager->flush();
    }
}
