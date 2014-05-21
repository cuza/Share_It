<?php

namespace BrightSoft\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\UserBundle\Entity\User;
use BrightSoft\UserBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Template()
     */
    public function indexAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="user_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="user_create")
     * @Method("POST")
     * @Template("UserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
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
                ->setDescription('User Created')
                ->setData($entity->getArray());
            $em->persist($log);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="user_update")
     * @Method("POST")
     * @Template("UserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('User Edited')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="user_delete")
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
            $entity = $em->getRepository('UserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('User Deleted')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
