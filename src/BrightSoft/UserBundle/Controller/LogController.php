<?php

namespace BrightSoft\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\UserBundle\Entity\Log;

/**
 * Log controller.
 *
 * @Route("/log")
 */
class LogController extends Controller
{
    /**
     * Lists all Log entities.
     *
     * @Route("/", name="log")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin')) {
            if ($user) $entities = $em->getRepository('UserBundle:Log')->findBy(array('user' => $user));
            else return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        } else $entities = $em->getRepository('UserBundle:Log')->findAll();

        $entities = array_reverse($entities);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Log entity.
     *
     * @Route("/{id}/show", name="log_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
        }

        $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
        if (!$user || ($entity->getUser()->getId() != $user->getId() && \BrightSoft\UserBundle\Lib\uBar::notRole($this, 'admin')))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);

       return array(
            'entity' => $entity,
        );
    }

}
