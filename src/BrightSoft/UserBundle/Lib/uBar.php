<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andy
 * Date: 9/12/12
 * Time: 20:51
 * To change this template use File | Settings | File Templates.
 */
namespace BrightSoft\UserBundle\Lib;

/**
 *
 */
class uBar
{
    /**
     * @param $request
     */
    public static function setTarget($request)
    {
        $session = $request->getSession();
        $session->set('uBar_target', $request->attributes->get('_route'));
    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller
     * @param Array $roles
     * @return bool
     */
    public static function notRole($controller, $roles)
    {
        if (is_string($roles))
            $roles = array($roles);
        $em = $controller->getDoctrine()->getManager();
        $request = $controller->getRequest();
        $session = $request->getSession();
        $userRepo = $em->getRepository('UserBundle:User');
        return !($userRepo->LoggedUser($session) && $userRepo->UserIs($session->get('user'), $roles));
    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller
     */
    public static function accessDenied($controller)
    {
//        uBar::setTarget($controller->getRequest());
        return $controller->redirect($controller->generateUrl('uDenied',array('route'=>$controller->getRequest()->attributes->get('_route'))));
    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller
     */
    public static function userLogged($controller)
    {
        $userRepo = $controller->getDoctrine()->getManager()->getRepository('UserBundle:User');
        $user = $controller->getRequest()->getSession()->get('user',null);
        if ($user)
            return $userRepo->find($user->getId());
        return null;
    }
}
