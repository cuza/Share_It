<?php

namespace BrightSoft\StorageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\StorageBundle\Entity\File;

/**
 * File controller.
 *
 * @Route("/file")
 */
class FileController extends Controller
{
    /**
     * Rename a File entity.
     *
     * @Route("/rename/{id}", name="file_rename")
     * @Template()
     */
    public function renameAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:File')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find File entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find File entity.');

        if ($this->getRequest()->request->has('name')) {
            $file = new \Symfony\Component\HttpFoundation\File\File($entity->getPath() . $entity->getStoreName());
            if (!file_exists($entity->getPath() . \BrightSoft\UserBundle\Lib\Utilities::getSlug($this->getRequest()->request->get('name')))) {
                $entity->setName($this->getRequest()->request->get('name'));
                $file->move($entity->getPath(), $entity->getStoreName());

                $em->persist($entity);
                $em->flush();

                return new \Symfony\Component\HttpFoundation\JsonResponse("OK");
            }
            $this->getRequest()->getSession()->set('msg', 'File already exist');
            return new \Symfony\Component\HttpFoundation\JsonResponse("storage");
        }
        return array(
            'entity' => $entity
        );
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/delete/{id}", name="file_delete")
     * @Template()
     */
    public function deleteAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:File')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find File entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find File entity.');
        if (file_exists($entity->getPath() . $entity->getStoreName()))
            unlink($entity->getPath() . $entity->getStoreName());
        $em->remove($entity);
        $em->flush();
        return new \Symfony\Component\HttpFoundation\JsonResponse("OK");
    }

    /**
     * Properties of a File entity.
     *
     * @Route("/prop/{id}", name="file_prop")
     * @Template()
     */
    public function propAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:File')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find File entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find File entity.');

        $prop = $entity->getArray();

        return array('entity' => $prop);
    }

    /**
     * Share a File entity.
     *
     * @Route("/share/{id}", name="file_share")
     */
    public function shareAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:File')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find File entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find File entity.');

        $entity->setAccess(!$entity->getAccess());

        $em->persist($entity);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse($entity->getAccess());
    }

    /**
     * Get Links of a File entity.
     *
     * @Route("/link/{id}", name="file_link")
     * @Template()
     */
    public function linkAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:File')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find File entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find File entity.');

        return array(
            'entity' => $entity
        );
    }
}
