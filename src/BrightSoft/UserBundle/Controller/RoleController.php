<?php

namespace BrightSoft\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\UserBundle\Entity\Role;
use BrightSoft\UserBundle\Form\RoleType;

/**
 * Role controller.
 *
 * @Route("/role")
 */
class RoleController extends Controller
{
    /**
     * Lists all Role entities.
     *
     * @Route("/", name="role")
     * @Template()
     */
    public function indexAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:Role')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/{id}/show", name="role_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Role entity.
     *
     * @Route("/new", name="role_new")
     * @Template()
     */
    public function newAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new Role();
        $form = $this->createForm(new RoleType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Role entity.
     *
     * @Route("/create", name="role_create")
     * @Method("POST")
     * @Template("UserBundle:Role:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new Role();
        $form = $this->createForm(new RoleType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Role Created')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            return $this->redirect($this->generateUrl('role_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("/{id}/edit", name="role_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $editForm = $this->createForm(new RoleType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Role entity.
     *
     * @Route("/{id}/update", name="role_update")
     * @Method("POST")
     * @Template("UserBundle:Role:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RoleType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Role Edited')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            return $this->redirect($this->generateUrl('role_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Role entity.
     *
     * @Route("/{id}/delete", name="role_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:Role')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Role entity.');
            }

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Role Deleted')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('role'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
