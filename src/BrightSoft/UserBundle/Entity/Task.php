<?php

namespace BrightSoft\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\UserBundle\Entity\Task
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\UserBundle\Entity\TaskRepository")
 */
class Task
{
    /**
     * @return string
     */
    public function __toString(){
        return $this->getName();
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'route' => $this->getRoute(),
            'icon' => $this->getIcon()
        );
    }

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", unique=true, type="string", length=255)
     */
    private $name;

    /**
     * @var string $route
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string $icon
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="tasks")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;


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
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug(\BrightSoft\UserBundle\Lib\Utilities::getSlug($name));
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Task
     */
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Task
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Task
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    
        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set role
     *
     * @param BrightSoft\UserBundle\Entity\Role $role
     * @return Task
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
}