<?php

namespace BrightSoft\StorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BrightSoft\StorageBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $msg = $session->get('msg', null);
        $session->remove('msg');
        return array('msg' => $msg);
    }


    /**
     * @Route("/storage", name="storage")
     * @Template()
     */
    public function storageAction()
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $root = $em->getRepository('StorageBundle:Folder')->getMyRootFolder($request);
        $msg = $session->get('msg', null);
        $session->remove('msg');
        return array('tree' => $root, 'msg' => $msg);
    }
}
