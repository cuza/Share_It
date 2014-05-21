<?php

namespace BrightSoft\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\UserBundle\Entity\Role
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\UserBundle\Entity\RoleRepository")
 */
class Role
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
            'description' => $this->getDescription()
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
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="role")
     */
    private $tasks;


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
     * @return Role
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
     * Set slug
     *
     * @param string $slug
     * @return Role
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
     * Set description
     *
     * @param string $description
     * @return Role
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add tasks
     *
     * @param BrightSoft\UserBundle\Entity\Task $tasks
     * @return Role
     */
    public function addTask(\BrightSoft\UserBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;
    
        return $this;
    }

    /**
     * Remove tasks
     *
     * @param BrightSoft\UserBundle\Entity\Task $tasks
     */
    public function removeTask(\BrightSoft\UserBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Get tasks
     *
     * @return Array(key=>value)
     */
    public function getTasksArray()
    {
        $tasks = array();
        foreach($this->tasks as $task)
            $tasks[]=array(
                'name'=>$task->getName(),
                'route'=>$task->getRoute(),
                'icon'=>$task->getIcon()
            );
        return $tasks;
    }
}