<?php

namespace BrightSoft\StorageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\StorageBundle\Entity\FileType;
use BrightSoft\StorageBundle\Form\FileTypeType;

/**
 * FileType controller.
 *
 * @Route("/filetype")
 */
class FileTypeController extends Controller
{
    /**
     * Lists all FileType entities.
     *
     * @Route("/", name="filetype")
     * @Template()
     */
    public function indexAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('StorageBundle:FileType')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FileType entity.
     *
     * @Route("/{id}/show", name="filetype_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StorageBundle:FileType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FileType entity.
     *
     * @Route("/new", name="filetype_new")
     * @Template()
     */
    public function newAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new FileType();
        $form = $this->createForm(new FileTypeType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new FileType entity.
     *
     * @Route("/create", name="filetype_create")
     * @Method("POST")
     * @Template("StorageBundle:FileType:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new FileType();
        $form = $this->createForm(new FileTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('FyleType Created')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            return $this->redirect($this->generateUrl('filetype_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FileType entity.
     *
     * @Route("/{id}/edit", name="filetype_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StorageBundle:FileType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileType entity.');
        }

        $editForm = $this->createForm(new FileTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FileType entity.
     *
     * @Route("/{id}/update", name="filetype_update")
     * @Method("POST")
     * @Template("StorageBundle:FileType:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StorageBundle:FileType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FileTypeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('FyleType Edited')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            return $this->redirect($this->generateUrl('filetype_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FileType entity.
     *
     * @Route("/{id}/delete", name="filetype_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('StorageBundle:FileType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FileType entity.');
            }

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('FyleType Deleted')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('filetype'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
