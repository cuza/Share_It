<?php

namespace BrightSoft\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\UserBundle\Entity\Log
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\UserBundle\Entity\LogRepository")
 */
class Log
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var array $data
     *
     * @ORM\Column(name="data", type="json_array")
     */
    private $data;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Log
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return Log
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set user
     *
     * @param BrightSoft\UserBundle\Entity\User $user
     * @return Log
     */
    public function setUser(\BrightSoft\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
        $this->setDate(new \DateTime('now'));
        return $this;
    }

    /**
     * Get user
     *
     * @return BrightSoft\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set role
     *
     * @param BrightSoft\UserBundle\Entity\Role $role
     * @return Log
     */
    public function setRole(\BrightSoft\UserBundle\Entity\Role $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return BrightSoft\UserBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Log
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}