<?php

namespace BrightSoft\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\StorageBundle\Entity\FileType
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\StorageBundle\Entity\FileTypeRepository")
 */
class FileType
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
            'description' => $this->getDescription(),
            'icon' => $this->getIcon(),
            'category'=>$this->getCategory().""
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="filetypes")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="type")
     */
    private $files;

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
     * @return FileType
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
     * @return FileType
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
     * @return FileType
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
     * @return FileType
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
     * Set category
     *
     * @param string $category
     * @return FileType
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add files
     *
     * @param BrightSoft\StorageBundle\Entity\File $files
     * @return FileType
     */
    public function addFile(\BrightSoft\StorageBundle\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param BrightSoft\StorageBundle\Entity\File $files
     */
    public function removeFile(\BrightSoft\StorageBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }
}