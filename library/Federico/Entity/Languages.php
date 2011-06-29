<?php
namespace Federico\Entity;
/**
 * @Table(name="languages")
 * @Entity
 * @author Federico Mendez
 * 
 */
class Languages {
    /**
     * @var integer
     * @Id @Column (name="id", type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;
    
    /**
     *
     * @var string
     * @Column(type="string") 
     */
    protected $languageName;
    
    /**
     *
     * @var User
     * @ManyToOne(targetEntity="User", inversedBy="languages") 
     * @JoinColumns({
     *  @JoinColumn(name="user_id", referencedColumnName="id", nullable="TRUE")
     * })
     */
    protected $user;
    
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }

	public function unsetUser() {
		$this->user = null;
	}
	
    public function getUser()
    {
        return $this->user;
    }
    
    public function getLanguageName()
    {
        return $this->languageName;
    }
    
    public function setLanguageName($languageName)
    {
        $this->languageName = $languageName;
        return $this->languageName;
    }  
}

?>
