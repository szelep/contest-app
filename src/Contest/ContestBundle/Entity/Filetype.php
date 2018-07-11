<?php

namespace ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filetype
 *
 * @ORM\Table(name="filetype")
 * @ORM\Entity(repositoryClass="ContestBundle\Repository\FiletypeRepository")
 */
class Filetype
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
     * @ORM\Column(name="value", type="string", length=255, unique=true)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="real_type", type="string", length=255)
     */
    private $realType;

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
     * Set value
     *
     * @param string $value
     *
     * @return Filetype
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set realType
     *
     * @param string $realType
     *
     * @return Filetype
     */
    public function setRealType($realType)
    {
        $this->realType = $realType;

        return $this;
    }

    /**
     * Get realType
     *
     * @return string
     */
    public function getRealType()
    {
        return $this->realType;
    }

    public function __toString()
    {
        return $this->value;
    }
}

