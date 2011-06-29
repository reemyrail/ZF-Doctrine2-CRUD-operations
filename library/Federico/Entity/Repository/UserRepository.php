<?php
namespace Federico\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUsersLanguages () {
        $users = $this->_em->createQuery('SELECT u FROM Federico\Entity\User u JOIN u.languages')->getResult();
        return $users;
    }
    
    public function getIdsUrls() {
        $users = $this->_em->createQuery('SELECT u.id, u.url FROM Federico\Entity\User u')->getArrayResult();
        $idsUrls = array();
        foreach ($users as $user) {
            $idsUrls[$user['id']] = $user['url'];
        }
        return $idsUrls;
    }
    
    public function getUser ($id) {
        $query = $this->_em->createQuery('SELECT u, l FROM Federico\Entity\User u JOIN u.languages l WHERE l.user = ?1');
        $query->setParameter(1, $id);
        $userModel = $query->getSingleResult();
        return $userModel;
    }
    
    public function getAllUrls () {
        $urls = $this->_em->createQuery('SELECT u.url FROM Federico\Entity\User u')->getResult();
        return $urls;
    }
}
?>
