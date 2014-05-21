<?php

namespace BrightSoft\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\UserBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\UserBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @return string
     */
    public function __toString(){
        return $this->getName();
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
     * @var string $pass
     *
     * @ORM\Column(name="pass", type="string", length=255)
     */
    private $pass;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", unique=true, type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     */
    private $roles;

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
     * @return User
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
     * @return User
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param \BrightSoft\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\BrightSoft\UserBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \BrightSoft\UserBundle\Entity\Role $roles
     */
    public function removeRole(\BrightSoft\UserBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set pass
     *
     * @param string $pass
     * @return User
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'pass' => $this->getPass(),
            'email' => $this->getEmail(),
            'error' => null
        );
    }
}