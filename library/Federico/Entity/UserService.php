<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Federico\Entity;
use Doctrine\ORM\EntityManager;
use Federico\Entity\User;
use Federico\Entity\Languages;
use Federico\Entity\UserRepository;
/**
 * Description of UserService
 *
 * @author fiodorovich
 */
class UserService {

    protected $em;
    protected $entity;
    protected $rep;

    public function __construct (EntityManager $em) {
        $this->em = $em;
        $this->entity = new User();
        $this->rep = $this->em->getRepository('Federico\Entity\User');
    }

	public function makeUser ($user) {
	    
	    $modelLanguages = array(); //temp var to store languages
        if (!$user['id'] == null) {
			$this->entity->setId($user['id']);
		}	
        $this->entity->setEmail($user['email']);
        $this->entity->setName($user['name']);
        $this->entity->setPassword($this->createPass($user['password']));
        $this->entity->setUrl($user['url']);
        $this->entity->setRole($user['role']);
        //set the languages collection
        foreach($user['languages'] as $language) {
            $l = new Languages();
            $l->setLanguageName($language);
            $l->setUser($this->entity);
            $modelLanguages[] = $l;
         }
        $this->entity->setLanguages($modelLanguages);

        return $this->entity;	    
    }
    
    public function saveUser (User $user) {
        $this->em->persist($user);
        $this->em->flush(); //save the user
    }

    public function updateUser (User $new) {
		$toRemove = array();
        $old = $this->rep->getUser($new->getId());
        if (!$old)
            throw new Exception('Error saving user!');
        foreach ($old->getLanguages() as $language) {
			if (!$new->hasLanguage($language)) {
				$toRemove[] = $language;
		    }
		}	
        //delete the countries I don't want anymore
        foreach ($toRemove as $language) {
			$old->removeLanguage($language);
			$this->em->remove($language);
		}
        //set the new countries...oh yeah ;)
        foreach ($new->getLanguages() as $language) {
			if (!$old->hasLanguage($language)) {
                $old->addLanguage($language);
                $this->em->persist($old);
            }
        }
		$this->saveUser($old); 
    }
    
    public function deleteUser($id) 
    {
        $entity = $this->em->find('Federico\Entity\User', $id); 

        if (!$entity) 
            echo 'Error deleting user!'; 
        $this->em->remove($entity); 
        $this->em->flush();
    }
       
    public static function createPass ($rawPass)
    {
		//yeah, I know... but I'm setting up this as a CRUD example, not how to implement
		//an unbreakable security layer.
        $password = crypt($rawPass, '$2a$07$ib3Ty0uw0n7cr4cKtH1ZmM$');
        return $password;
    }
}

?>
