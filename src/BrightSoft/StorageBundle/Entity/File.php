<?php

namespace BrightSoft\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrightSoft\StorageBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BrightSoft\StorageBundle\Entity\FileRepository")
 */


class File
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
            'slug' => $this->getSlug(),
            'ext' => $this->getExt(),
            'icon' => $this->getType()->getIcon(),
            'size' => $this->getSize(),
            'mDate' => $this->getModificationDate()->format('m/d/Y'),
            'shared' => $this->getAccess(),
            'relpath' => $this->getRelPath()
        );
    }

    public function getPath()
    {
        return $this->getFolder()->getPath();
    }

    public function getRelPath()
    {
        return $this->getFolder()->getRelPath().$this->getStoreName();
    }

    public function getStoreName()
    {
        return $this->getSlug() . '.' . $this->getExt();
    }

    /**
     * Get owner
     *
     * @return BrightSoft\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->getFolder()->getOwner();
    }

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private
        $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private
        $name;

    /**
     * @var string $ext
     *
     * @ORM\Column(name="ext", type="string", length=255)
     */
    private
        $ext;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private
        $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="files")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="id")
     */
    private
        $folder;

    /**
     * @ORM\ManyToOne(targetEntity="FileType", inversedBy="files")
     * @ORM\JoinColumn(name="filetype_id", referencedColumnName="id")
     */
    private
        $type;

    /**
     * @var \DateTime $creation_date
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private
        $creation_date;

    /**
     * @var \DateTime $modification_date
     *
     * @ORM\Column(name="modification_date", type="datetime")
     */
    private
        $modification_date;

    /**
     * @var float $size
     *
     * @ORM\Column(name="size", type="float")
     */
    private
        $size;

    /**
     * @var boolean $access
     *
     * @ORM\Column(name="access", type="boolean")
     */
    private
        $access;


    /**
     * Get id
     *
     * @return integer
     */
    public
    function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return File
     */
    public
    function setName($name)
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
    public
    function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return File
     */
    public
    function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public
    function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set creation_date
     *
     * @param \DateTime $creationDate
     * @return File
     */
    public
    function setCreationDate($creationDate)
    {
        $this->creation_date = $creationDate;

        return $this;
    }

    /**
     * Get creation_date
     *
     * @return \DateTime
     */
    public
    function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Set size
     *
     * @param float $size
     * @return File
     */
    public
    function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float
     */
    public
    function getSize()
    {
        $unit = array(' b', ' kb', ' mb', ' Gb');
        $i = 0;
        $tmp = $this->size;
        while ($tmp / pow(1024.0, $i + 1) > 1)
            $i++;
        return round($tmp / pow(1024.0, $i), 2) . $unit[$i];
    }

    /**
     * Set access
     *
     * @param boolean $access
     * @return File
     */
    public
    function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return boolean
     */
    public
    function getAccess()
    {
        return $this->access;
    }

    /**
     * Set folder
     *
     * @param BrightSoft\StorageBundle\Entity\Folder $folder
     * @return File
     */
    public
    function setFolder(\BrightSoft\StorageBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return BrightSoft\StorageBundle\Entity\Folder
     */
    public
    function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set type
     *
     * @param BrightSoft\StorageBundle\Entity\FileType $type
     * @return File
     */
    public
    function setType(\BrightSoft\StorageBundle\Entity\FileType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return BrightSoft\StorageBundle\Entity\FileType
     */
    public
    function getType()
    {
        return $this->type;
    }

    /**
     * Set modification_date
     *
     * @param \DateTime $modificationDate
     * @return File
     */
    public function setModificationDate($modificationDate)
    {
        $this->modification_date = $modificationDate;

        return $this;
    }

    /**
     * Get modification_date
     *
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modification_date;
    }

    /**
     * Set ext
     *
     * @param string $ext
     * @return File
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }
}