<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Federico\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of User
 * @Table(name="users")
 * @Entity(repositoryClass="Federico\Entity\Repository\UserRepository")
 * @author Federico Mendez
 */
class User {

    /**
     * @var integer
     * @Id @Column (name="id", type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;
    /**
     * @Column(type="string",length=60,nullable=true, unique=true)
     * @var string
     */
    private $email;
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    private $password;
    /**
     * @Column(type="string",length=60,nullable=true,unique=true)
     * @var string
     */
    private $url;
    /**
     * @Column(type="string",length=60,nullable=true,unique=true)
     * @var string
     */
    private $name;
    /**
     * @Column(type="string",length=20,nullable=true)
     * @var string
     */
    private $role;
    /**
     *
     * @var datetime
     * @Column(type="datetime", nullable=false)
     */
    private $created;
    
    /**
     * 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @OneToMany(targetEntity="Languages",mappedBy="user", cascade={"persist", "remove"})
     */
    protected $languages;
   
    public function __construct () {
        $this->created = new \DateTime(date('Y-m-d H:i:s'));
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function setId ($id) {
		$this->id = $id;
	}	
		
    public function getId () {
        return $this->id;
    }

    public function getEmail () {
        return $this->email;
    }

    public function setEmail ($email) {
        $this->email = $email;
        return $this->email;
    }

    public function getRole () {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this->role;
    }

    public function getName () {
        return $this->name;
    }

    public function setName ($name) {
        $this->name = $name;
        return $this->name;
    }

    public function getPassword () {
        return $this->password;
    }

    public function setPassword ($password) {
        $this->password = $password;
        return $this->password;
    }

    public function getUrl () {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this->url;
    }

    public function getCreated() {
        return $this->created;
    }

    /* Languages methods */
    public function setLanguages ($languages) {
        $this->languages = $languages;
        return $this->languages;
    }

    public function getLanguages () {
        return $this->languages;
    }

	public function hasLanguage (Languages $language) {
		$languages = array();
		foreach ($this->getLanguages() as $arrMember) {
			$languages[] = $arrMember->getLanguageName();
		}
		if (in_array($language->getLanguageName(), $languages))    //check if the supplied language is to be removed or not
		    return true;
	}
		
    public function removeLanguage (Languages $language) {
        $this->languages->removeElement($language);
        $language->unsetUser();
    }
    
    public function addLanguage (Languages $language) {
		$language->setUser($this);
		$this->languages[] = $language;
    }
    /* end Languages methods */
}

?>
