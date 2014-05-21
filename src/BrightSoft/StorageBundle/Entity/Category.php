<?php

namespace BrightSoft\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\StorageBundle\Entity\Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\StorageBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    public function getArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
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
     * @ORM\Column(name="name", type="string", length=255)
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
     * @var string $icon
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="FileType", mappedBy="category")
     */
    protected $filetypes;

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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Set icon
     *
     * @param string $icon
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->filetypes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add filetypes
     *
     * @param BrightSoft\StorageBundle\Entity\FileType $filetypes
     * @return Category
     */
    public function addFiletype(\BrightSoft\StorageBundle\Entity\FileType $filetypes)
    {
        $this->filetypes[] = $filetypes;

        return $this;
    }

    /**
     * Remove filetypes
     *
     * @param BrightSoft\StorageBundle\Entity\FileType $filetypes
     */
    public function removeFiletype(\BrightSoft\StorageBundle\Entity\FileType $filetypes)
    {
        $this->filetypes->removeElement($filetypes);
    }

    /**
     * Get filetypes
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getFiletypes()
    {
        return $this->filetypes;
    }
}