<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andy
 * Date: 12/12/12
 * Time: 13:18
 * To change this template use File | Settings | File Templates.
 */
namespace BrightSoft\StorageBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use BrightSoft\UserBundle\Entity\User;
use BrightSoft\StorageBundle\Entity\Folder;

class UserListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof User
            && $em->getRepository('UserBundle:User')->UserIs($entity, array('user'))
        ) {
            $root_f = new Folder();
            $root_f->setCreationDate(new \DateTime('now'))
                ->setName($entity->getSlug())
                ->setOwner($entity)
                ->setReadOnly(true);
            $root_f->setIcon('folder-root');

            $cat = $em->getRepository('StorageBundle:Category')->findAll();
            foreach ($cat as $c) {
                $folder = new Folder();
                $folder->setCreationDate(new \DateTime('now'))
                    ->setName($c->getName())
                    ->setOwner($root_f->getOwner())
                    ->setParent($root_f)
                    ->setReadOnly(true);
                $folder->setIcon($folder->getSlug());
                $root_f->addChildren($folder);
            }

            $em->persist($root_f);
            $em->flush();
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof User) {
            $root_f = $em->getRepository('StorageBundle:Folder')->findOneBy(array(
                'owner' => $entity,
                'parent' => null
            ));
            if ($root_f) {
                $root_f->setName($entity->getSlug());
                $em->persist($root_f);
                $em->flush();
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof User) {
            $root_f = $em->getRepository('StorageBundle:Folder')->findOneBy(array(
                'owner' => $entity,
                'parent' => null
            ));
            if ($root_f) {
                $em->remove($root_f);
                $em->flush();
            }
        }
    }

}
