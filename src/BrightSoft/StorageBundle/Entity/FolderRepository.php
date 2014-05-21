<?php

namespace BrightSoft\StorageBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FolderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FolderRepository extends EntityRepository
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function getMyRootFolder($request)
    {
        $session = $request->getSession();
        $data = $session->get('user-data');
        if (array_key_exists('root_f',$data))
            return $data['root_f'];
        $user = $session->get('user');
        $data['root_f'] = $this->findOneBy(array('owner' => $user, 'parent' => null))->getTreeArray();
        $session->set('user-data',$data );
        return $data['root_f'];
    }
}
