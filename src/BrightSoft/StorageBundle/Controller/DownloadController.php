<?php

namespace BrightSoft\StorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use BrightSoft\StorageBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DownloadController extends Controller
{
    /**
     * @Route("/download/{id}/{slug}",defaults={"slug"=""}, name="download_file")
     * @Template()
     */
    public function downloadAction($id)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        /* @var \BrightSoft\StorageBundle\Entity\FileRepository $fileRepo */
        $fileRepo = $em->getRepository('StorageBundle:File');
        $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);

        $query = $fileRepo
            ->createQueryBuilder('f')
            ->select(array('f', 'F'))
            ->join('f.folder', 'F')
            ->where('f.id = :id')
            ->andWhere('f.access = true OR F.owner = :user')
            ->setParameters(array('id' => $id, 'user' => $user))
            ->getQuery();

        try {
            $entity = $query->getSingleResult();
        } catch (\Exception $exc) {
            throw $this->createNotFoundException();
        }

        if (!$entity)
            throw $this->createNotFoundException();

        return array('entity' => $entity->getArray());
    }

    /**
     * @Route("/get/{id}", name="get_file")
     */
    public function getAction($id)
    {

        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        /* @var \BrightSoft\StorageBundle\Entity\FileRepository $fileRepo */
        $fileRepo = $em->getRepository('StorageBundle:File');
        $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);

        $query = $fileRepo
            ->createQueryBuilder('f')
            ->select(array('f', 'F'))
            ->join('f.folder', 'F')
            ->where('f.id = :id')
            ->andWhere('f.access = true OR F.owner = :user')
            ->setParameters(array('id' => $id, 'user' => $user))
            ->getQuery();
        /* @var \BrightSoft\StorageBundle\Entity\File $entity */

        try {
            $entity = $query->getSingleResult();
        } catch (\Exception $exc) {
            throw $this->createNotFoundException();
        }
        if (!$entity)
            throw $this->createNotFoundException();

        $expired = false;
        if ($expired)
            return $this->redirect($this->generateUrl('download_file', array('id' => $id, 'slug' => $entity->getSlug())));

        $response = new Response();

        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $entity->getStoreName());
        $response->headers->set('Content-Disposition', $d);
        $response->headers->set("X-Accel-Redirect", '/' . $entity->getRelPath());

        return $response;
    }

    /**
     * @Route("/shared/{user}", name="shared_user")
     */
    public function shared_userAction($user)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $user = \BrightSoft\UserBundle\Lib\Utilities::getSlug($user);

        $query = $em->getRepository('StorageBundle:File')
            ->createQueryBuilder('f')
            ->select(array('f', 'F', 'u'))
            ->join('f.folder', 'F')
            ->join('F.owner', 'u')
            ->where('u.slug = :slug AND f.access = true')
            ->setParameters(array('slug' => $user))
            ->getQuery();

        $files = array();
        foreach ($query->getResult() as $file)
            $files[] = $file->getArray();

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $files,
            $this->getRequest()->query->get('page', 1), //page number
            20
        );

        return $this->render('StorageBundle:Download:shared.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction()
    {
        $user = \BrightSoft\UserBundle\Lib\uBar::userLogged($this);
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $search = $this->getRequest()->query->get('q', '');
        $filter = $this->getRequest()->query->get('f', '');
        $search = preg_split('@[^a-zA-Z0-9]+@', $search);
        $query = $em->getRepository('StorageBundle:File')
            ->createQueryBuilder('f')
            ->select(array('f', 'F', 't', 'c'))
            ->join('f.folder', 'F')
            ->join('f.type', 't')
            ->join('t.category', 'c')
            ->where('(F.owner = :user OR f.access = true)');
        $param = array('user' => $user);
        if ($filter != '') {
            $param['filter'] = "%$filter%";
            $query->andWhere('c.name LIKE :filter');
        }
        foreach ($search as $q) {
            $param['name' . (count($param) + 1)] = "%$q%";
            $query->andWhere('f.name LIKE :name' . count($param) . ' OR f.ext LIKE :name' . count($param));
        }
        $query = $query->setParameters($param)->getQuery();
        $files = array();
        foreach ($query->getResult() as $file)
            $files[] = $file->getArray();

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $files,
            $this->getRequest()->query->get('page', 1), //page number
            20
        );

        return $this->render('StorageBundle:Download:shared.html.twig', array('pagination' => $pagination, 'query' => $this->getRequest()->query->get('q', null)));
    }

    /**
     * @Route("/shared", name="shared")
     * @Template()
     */
    public function sharedAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('StorageBundle:File')
            ->createQueryBuilder('f')
            ->select(array('f'))
            ->where('f.access = true')
            ->getQuery();
        $files = array();
        foreach ($query->getResult() as $file)
            $files[] = $file->getArray();

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $files,
            $this->getRequest()->query->get('page', 1), //page number
            20
        );

        return array('pagination' => $pagination);
    }
}
