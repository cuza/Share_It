<?php

namespace BrightSoft\StorageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\StorageBundle\Entity\Category;
use BrightSoft\StorageBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category")
     * @Template()
     */
    public function indexAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('StorageBundle:Category')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}/show", name="category_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StorageBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="category_new")
     * @Template()
     */
    public function newAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new Category();
        $form   = $this->createForm(new CategoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/create", name="category_create")
     * @Method("POST")
     * @Template("StorageBundle:Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity  = new Category();
        $form = $this->createForm(new CategoryType(), $entity);
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
                ->setDescription('Category Created')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            return $this->redirect($this->generateUrl('category_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StorageBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createForm(new CategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}/update", name="category_update")
     * @Method("POST")
     * @Template("StorageBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'file_manager'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StorageBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CategoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Category Edited')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            return $this->redirect($this->generateUrl('category_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}/delete", name="category_delete")
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
            $entity = $em->getRepository('StorageBundle:Category')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Category entity.');
            }

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'file_manager'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Category Deleted')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('category'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
