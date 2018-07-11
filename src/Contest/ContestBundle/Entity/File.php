<?php

namespace ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="ContestBundle\Repository\FileRepository")
 */
class File
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=255)
     */
    private $originalName;

    /**
     * @var string
     *
     * @ORM\Column(name="temp_name", type="string", length=255, unique=true)
     */
    private $tempName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255, unique=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="file_size", type="string", length=255, unique=true)
     */
    private $fileSize;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=255)
     */
    private $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="post", type="string", length=255)
     */
    private $post;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     *
     * @return File
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set tempName
     *
     * @param string $tempName
     *
     * @return File
     */
    public function setTempName($tempName)
    {
        $this->tempName = $tempName;

        return $this;
    }

    /**
     * Get tempName
     *
     * @return string
     */
    public function getTempName()
    {
        return $this->tempName;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set post
     *
     * @param string $post
     *
     * @return File
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Get the value of extension
     *
     * @return  string
     */ 
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of extension
     *
     * @param  string  $extension
     *
     * @return  self
     */ 
    public function setExtension(string $extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get the value of fileSize
     *
     * @return  string
     */ 
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set the value of fileSize
     *
     * @param  string  $fileSize
     *
     * @return  self
     */ 
    public function setFileSize(string $fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }
}

