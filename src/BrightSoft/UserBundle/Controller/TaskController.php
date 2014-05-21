<?php

namespace BrightSoft\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\UserBundle\Entity\Task;
use BrightSoft\UserBundle\Form\TaskType;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task")
     * @Template()
     */
    public function indexAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:Task')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("/{id}/show", name="task_show")
     * @Template()
     */
    public function showAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="task_new")
     * @Template()
     */
    public function newAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new Task();
        $form = $this->createForm(new TaskType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/create", name="task_create")
     * @Method("POST")
     * @Template("UserBundle:Task:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $entity = new Task();
        $form = $this->createForm(new TaskType(), $entity);
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
                ->setDescription('Task Created')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}/update", name="task_update")
     * @Method("POST")
     * @Template("UserBundle:Task:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TaskType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Task Edited')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/{id}/delete", name="task_delete")
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
            $entity = $em->getRepository('UserBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $log = new \BrightSoft\UserBundle\Entity\Log();
            $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
            $role = $em->getRepository('UserBundle:Role')->findOneBy(array('name' => 'admin'));
            $log->setUser($user)
                ->setRole($role)
                ->setDescription('Task Deleted')
                ->setData($entity->getArray());
            $em->persist($log);$em->flush();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
