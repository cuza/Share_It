<?php

namespace BrightSoft\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class UserBarController extends Controller
{
    /**
     * @Route("/uBar", name="uBar")
     * @Template()
     */
    public function uBarAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $_target = $session->get('uBar_target', 'index');
        $em = $this->getDoctrine()->getManager();
        if ($request->query->has('logout')){
            $session->remove('user');
            $session->remove('user-data');
        }
        if ($request->request->has('user') && $request->request->has('pass'))
            $em->getRepository('UserBundle:User')
                ->Login($request);
        if ($request->request->has('_target_path'))
            return $this->redirect($this->generateUrl($request->request->get('_target_path')));
        $user = $session->get('user',
            $session->get('user_error', array(
                    'name' => '',
                    'email' => '',
                    'error' => null)
            )
        );
        $tasks = null;
        if ($user instanceof \BrightSoft\UserBundle\Entity\User) {
            $tasks = $em->getRepository('UserBundle:User')->GetTasks($user);
            $user = $user->getArray();
        }
        $session->remove('user_error');
        return array(
            'user' => $user,
            '_target' => $_target,
            'ajax' => ($request->request->has('ajax') || $request->query->has('logout')),
            'tasks' => $tasks
        );
    }

    /**
     * @Route("/uNew/{_target}", defaults={"_target"="index"}, name="uNew")
     * @Template()
     */
    public function uNewAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $_target = $session->get('uBar_target', 'index');
        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user', $session->get('user_error', array(
                    'id' => -1,
                    'name' => '',
                    'email' => '',
                    'error' => null,
                    'pass' => '')
            )
        );
        if ($user instanceof \BrightSoft\UserBundle\Entity\User)
            $user = $user->getArray();
        foreach ($user as $key => $val)
            if ($request->request->has($key))
                $user[$key] = $request->request->get($key);
        if ($user['name'] != '' && $user['email'] != '')
            $user = $em->getRepository('UserBundle:User')
                ->Create($user, $this);
        else if ($request->request->has('_target_path'))
            $user['error'] = array(
                'name' => true,
                'email' => true,
                'pass' => false
            );
        $session->remove('user_error');
        if ($user['error'] != null)
            return array(
                'user' => $user,
                '_target' => $_target
            );
        if ($request->request->has('_target_path'))
            return $this->redirect($this->generateUrl($request->request->get('_target_path')));
        return array(
            'user' => $user,
            '_target' => $_target
        );
    }

    /**
     * @Route("/uDenied", name="uDenied")
     * @Template()
     */
    public function uDeniedAction()
    {
        $route=$this->getRequest()->query->get('route','index');
        return array('route'=>$route);
    }

    /**
     * @Route("/", name="test")
     * @Template()
     */
    public function testAction()
    {
        \BrightSoft\UserBundle\Lib\uBar::setTarget($this->getRequest());
        return array();
    }
}
