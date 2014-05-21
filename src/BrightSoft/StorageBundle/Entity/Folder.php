<?php

namespace BrightSoft\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\StorageBundle\Entity\Folder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\StorageBundle\Entity\FolderRepository")
 */
class Folder
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return ($this->getParent()?$this->getParent()->getPath():__DIR__ . '/../../../../web/upload/').$this->getSlug().'/';
    }

    /**
     * @return string
     */
    public function getRelPath()
    {
        return ($this->getParent()?$this->getParent()->getRelPath():'upload/').$this->getSlug().'/';
    }

    /**
     * @return array(int)
     */
    public function getCounts()
    {
        $count = array(
            'files' => 0,
            'folders' => 0
        );
        foreach ($this->getChildren() as $folder) {
            $temp = $folder->getCounts();
            $count['files'] += ($temp['files']);
            $count['folders'] += ($temp['folders'] + 1);
        }
        return $count;
    }

    /**
     * @return array()
     */
    public function getTreeArray()
    {
        $tree = array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'icon' => $this->getIcon(),
            'files' => count($this->getFiles()),
            'children' => null
        );
        $children = $this->getChildren();
        if (count($children)) {
            $tree['children'] = array();
            foreach ($children as $child)
                $tree['children'][] = $child->getTreeArray();
        }
        return $tree;
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
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="\BrightSoft\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var string $icon
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="parent", cascade={"persist","remove"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="folder", cascade={"persist","remove"})
     */
    private $files;

    /**
     * @var \DateTime $creation_date
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creation_date;

    /**
     * @var boolean $read_only
     *
     * @ORM\Column(name="read_only", type="boolean")
     */
    private $read_only;


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
     * @return Folder
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
     * @return Folder
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
     * Set creation_date
     *
     * @param \DateTime $creationDate
     * @return Folder
     */
    public function setCreationDate($creationDate)
    {
        $this->creation_date = $creationDate;

        return $this;
    }

    /**
     * Get creation_date
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add children
     *
     * @param BrightSoft\StorageBundle\Entity\Folder $children
     * @return Folder
     */
    public function addChildren(\BrightSoft\StorageBundle\Entity\Folder $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param BrightSoft\StorageBundle\Entity\Folder $children
     */
    public function removeChildren(\BrightSoft\StorageBundle\Entity\Folder $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Add files
     *
     * @param BrightSoft\StorageBundle\Entity\File $files
     * @return Folder
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

    /**
     * Set parent
     *
     * @param BrightSoft\StorageBundle\Entity\Folder $parent
     * @return Folder
     */
    public function setParent(\BrightSoft\StorageBundle\Entity\Folder $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return BrightSoft\StorageBundle\Entity\Folder
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set owner
     *
     * @param BrightSoft\UserBundle\Entity\User $owner
     * @return Folder
     */
    public function setOwner(\BrightSoft\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return BrightSoft\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set read_only
     *
     * @param boolean $readOnly
     * @return Folder
     */
    public function setReadOnly($readOnly)
    {
        $this->read_only = $readOnly;

        return $this;
    }

    /**
     * Get read_only
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->read_only;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Folder
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
}