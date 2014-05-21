<?php

namespace BrightSoft\StorageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrightSoft\StorageBundle\Entity\Folder;
use BrightSoft\StorageBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Folder controller.
 *
 * @Route("/folder")
 */
class FolderController extends Controller
{
    /**
     * Upload a File entity.
     *
     * @Route("/_upload/", name="_upload")
     */
    public function _uploadAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->findOneBy(array('name' => 'anonymous'));
        $entity = $em->getRepository('StorageBundle:Folder')->findOneBy(array('owner' => $user, 'parent' => null));

        $uploaded = array();
        $error = array();
        $msg = '';
        foreach ($this->getRequest()->files as $file) {
            /* @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            if ($file && !$file->getError()) {
                $name = $file->getClientOriginalName();
                $tmp = explode('.', $name);
                $ext = $tmp[count($tmp) - 1];

                $name = substr($name, 0, -(1 + strlen($ext)));
                $myFile = new File();
                $date = new \DateTime();
                $myFile->setName($name)
                    ->setCreationDate($date->setTimestamp($file->getCTime()))
                    ->setModificationDate($date->setTimestamp($file->getMTime()))
                    ->setSize($file->getSize())
                    ->setAccess(true)
                    ->setFolder($entity)
                    ->setExt($ext);
                $type = $em->getRepository('StorageBundle:FileType')->findOneBy(array('name' => strtolower($ext)));
                if (!$type) $type = $em->getRepository('StorageBundle:FileType')->findOneBy(array('name' => 'Default'));
                $myFile->setType($type);
var_dump($entity);
                if (!file_exists($entity->getPath() . $myFile->getStoreName())) {
                    $file->move($entity->getPath(), $myFile->getStoreName());
                    $em->persist($myFile);
                    $uploaded[] = $file->getClientOriginalName();
                } else
                    $error[] = $file->getClientOriginalName() . '(Already exist)';
            } else if ($file) {
                $error[] = $file->getClientOriginalName() . '(Upload error)';
            }
        }
        $em->flush();

        if (count($uploaded)) {
            $msg .= 'Uploaded: ' . count($uploaded) . '<br>';
            foreach ($uploaded as $u) $msg .= $u . '<br>';
        }
        if (count($error)) {
            $msg .= 'Error: ' . count($error) . '<br>';
            foreach ($error as $e) $msg .= $e . '<br>';
        }
        $this->getRequest()->getSession()->set('msg', $msg);
        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * Upload a File entity.
     *
     * @Route("/upload_url/{id}", name="folder_upload_url")
     * @Template()
     */
    public function upload_urlAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        $uploaded = array();
        $error = array();
        $msg = '';
        for ($i = 0; $i < $this->getRequest()->request->count() / 3; $i++) {
            $url = $this->getRequest()->request->get('upl_url_'.$i,null);
            $name =  $this->getRequest()->request->get('upl_name_'.$i,null);
            $ref =  $this->getRequest()->request->get('upl_ref_'.$i,null);
            if(true /*TODO: valid url and name and set error*/){
                $fp = fopen($path, 'w');

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/8.0 GTB5');
                if (!empty($_POST["referer"]))
                    curl_setopt($ch, CURLOPT_REFERER, $_POST["referer"]);

                $data = curl_exec($ch);

                curl_close($ch);
                fclose($fp);

                if (true /*TODO: valid downloaded file*/) {
                    $name = "";
                    $tmp = explode('.', $name);
                    $ext = $tmp[count($tmp) - 1];
                    $name = substr($name, 0, -(1 + strlen($ext)));
                    $myFile = new File();
                    $date = new \DateTime();
                    $myFile->setName($name)
                        ->setCreationDate($date->setTimestamp($file->getCTime()))
                        ->setModificationDate($date->setTimestamp($file->getMTime()))
                        ->setSize($file->getSize())
                        ->setAccess(false)
                        ->setFolder($entity)
                        ->setExt($ext);
                    $type = $em->getRepository('StorageBundle:FileType')->findOneBy(array('name' => strtolower($ext)));
                    if (!$type) $type = $em->getRepository('StorageBundle:FileType')->findOneBy(array('name' => 'Default'));
                    $myFile->setType($type);

                    if (!file_exists($entity->getPath() . $myFile->getStoreName())) {
                        $file->move($entity->getPath(), $myFile->getStoreName());
                        $em->persist($myFile);
                        $uploaded[] = $file->getClientOriginalName();
                    } else
                        $error[] = $file->getClientOriginalName() . '(Already exist)';
                }else{
                    //TODO: SET DOWNLOAD ERROR
                }
            }
        }

        $em->flush();

        if (count($uploaded)) {
            $msg .= 'Uploaded: ' . count($uploaded) . '<br>';
            foreach ($uploaded as $u) $msg .= $u . '<br>';
        }
        if (count($error)) {
            $msg .= 'Error: ' . count($error) . '<br>';
            foreach ($error as $e) $msg .= $e . '<br>';
        }
        $this->getRequest()->getSession()->set('msg', $msg);
        if ($this->getRequest()->request->get('_storage_url'))
            return $this->redirect($this->generateUrl('storage'));
        return array('entity' => $entity);
    }

    /**
     * Upload a File entity.
     *
     * @Route("/upload/{id}", name="folder_upload")
     * @Template()
     */
    public function uploadAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        $uploaded = array();
        $error = array();
        $msg = '';
        foreach ($this->getRequest()->files as $file) {
            /* @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            if ($file && !$file->getError()) {
                $name = $file->getClientOriginalName();
                $tmp = explode('.', $name);
                $ext = $tmp[count($tmp) - 1];
                $name = substr($name, 0, -(1 + strlen($ext)));
                $myFile = new File();
                $date = new \DateTime();
                $myFile->setName($name)
                    ->setCreationDate($date->setTimestamp($file->getCTime()))
                    ->setModificationDate($date->setTimestamp($file->getMTime()))
                    ->setSize($file->getSize())
                    ->setAccess(false)
                    ->setFolder($entity)
                    ->setExt($ext);
                $type = $em->getRepository('StorageBundle:FileType')->findOneBy(array('name' => strtolower($ext)));
                if (!$type) $type = $em->getRepository('StorageBundle:FileType')->findOneBy(array('name' => 'Default'));
                $myFile->setType($type);

                if (!file_exists($entity->getPath() . $myFile->getStoreName())) {
                    $file->move($entity->getPath(), $myFile->getStoreName());
                    $em->persist($myFile);
                    $uploaded[] = $file->getClientOriginalName();
                } else
                    $error[] = $file->getClientOriginalName() . '(Already exist)';
            } else if ($file) {
                $error[] = $file->getClientOriginalName() . '(Upload error)';
            }
        }
        $em->flush();

        if (count($uploaded)) {
            $msg .= 'Uploaded: ' . count($uploaded) . '<br>';
            foreach ($uploaded as $u) $msg .= $u . '<br>';
        }
        if (count($error)) {
            $msg .= 'Error: ' . count($error) . '<br>';
            foreach ($error as $e) $msg .= $e . '<br>';
        }
        $this->getRequest()->getSession()->set('msg', $msg);
        if ($this->getRequest()->request->get('_storage_url'))
            return $this->redirect($this->generateUrl('storage'));
        return array('entity' => $entity);
    }

    /**
     * Creates a new Folder entity.
     *
     * @Route("/create/{id}", name="folder_create")
     * @Template()
     */
    public function createAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        if ($this->getRequest()->request->has('name')) {
            $folder = new Folder();
            $folder->setCreationDate(new \DateTime('now'))
                ->setName($this->getRequest()->request->get('name'))
                ->setOwner($entity->getOwner())
                ->setParent($entity)
                ->setReadOnly(false)
                ->setIcon('icon');
            $entity->addChildren($folder);

            $em->persist($entity);
            $em->flush();

            $data = $this->getRequest()->getSession()->get('user-data');
            unset($data['root_f']);
            $this->getRequest()->getSession()->set('user-data', $data);

            return new \Symfony\Component\HttpFoundation\JsonResponse("OK");
        }
        return array(
            'entity' => $entity
        );
    }

    /**
     * Rename a Folder entity.
     *
     * @Route("/rename/{id}", name="folder_rename")
     * @Template()
     */
    public function renameAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (!$entity->getParent())
            throw $this->createNotFoundException('Root Folder is ReadOnly.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        if ($this->getRequest()->request->has('name')) {
            $old = $entity->getPath();
            $entity->setName($this->getRequest()->request->get('name'));
            $new = $entity->getPath();

            if (file_exists($old))
                rename($old, $new);
            $em->persist($entity);
            $em->flush();

            $data = $this->getRequest()->getSession()->get('user-data');
            unset($data['root_f']);
            $this->getRequest()->getSession()->set('user-data', $data);

            return new \Symfony\Component\HttpFoundation\JsonResponse("OK");
        }
        return array(
            'entity' => $entity
        );
    }

    /**
     * Deletes a Folder entity.
     *
     * @Route("/delete/{id}", name="folder_delete")
     * @Template()
     */
    public function deleteAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (!$entity->getParent())
            throw $this->createNotFoundException('Root Folder is ReadOnly.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        if ($this->getRequest()->request->has('delete')) {
            if (is_dir($entity->getPath()))
                $this->rmDir($entity->getPath());
            $em->remove($entity);
            $em->flush();

            $data = $this->getRequest()->getSession()->get('user-data');
            unset($data['root_f']);
            $this->getRequest()->getSession()->set('user-data', $data);
            return new \Symfony\Component\HttpFoundation\JsonResponse("OK");
        }
        return array(
            'entity' => $entity
        );
    }

    function rmDir($path)
    {
        $d = dir($path);
        if ($path[strlen($path) - 1] != '\\')
            $path .= '\\';
        while (false !== ($entry = $d->read())) {
            if ($entry != '.' && $entry != '..') {
                if (is_dir($path . $entry))
                    $this->rmDir($path . $entry);
                else
                    unlink($path . $entry);
            }
        }
        rmdir($path);
    }

    /**
     * Properties of a Folder entity.
     *
     * @Route("/prop/{id}", name="folder_prop")
     * @Template()
     */
    public function propAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        $temp = $entity->getCounts();
        $prop = array(
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'c_date' => $entity->getCreationDate(),
            'files' => $temp['files'],
            'folders' => $temp['folders']
        );

        return array('entity' => $prop);
    }

    /**
     * Properties of a Folder entity.
     *
     * @Route("/files/{id}", name="folder_files")
     */
    public function filesAction($id)
    {
        if (\BrightSoft\UserBundle\Lib\uBar::notRole($this, 'user'))
            return \BrightSoft\UserBundle\Lib\uBar::accessDenied($this);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StorageBundle:Folder')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Folder entity.');
        if (\BrightSoft\UserBundle\Lib\uBar::userLogged($this)->getId() != $entity->getOwner()->getId())
            throw $this->createNotFoundException('Unable to find Folder entity.');

        $files = array();
        foreach ($entity->getFiles() as $file)
            $files[] = $file->getArray();

        $bc_last = array('id' => $entity->getId(), 'name' => $entity->getName());
        $bc = array();
        $tmp = $entity->getParent();
        while ($tmp != null) {
            $bc[] = array('id' => $tmp->getId(), 'name' => $tmp->getName());
            $tmp = $tmp->getParent();
        }
        $bc = array_reverse($bc);

        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'files' => $files,
            'bc' => $bc,
            'bc_last' => $bc_last
        ));
    }
}
