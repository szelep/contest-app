<?php

namespace ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ContestBundle\Entity\Contest;
use ContestBundle\Entity\File;

/**
 * Template
 *
 * @ORM\Table(name="template")
 * @ORM\Entity(repositoryClass="ContestBundle\Repository\TemplateRepository")
 */
class Template
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
     * 
     * @ORM\OneToOne(targetEntity="Contest", inversedBy="template")
     * @ORM\JoinColumn(name="contest_id", referencedColumnName="id")
     */
    private $contest;

    /**
     * @var File
     * 
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="thumbnail_id", referencedColumnName="id")
     */
    protected $thumbnail;

    /**
     * @var File
     * 
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="header_image_id", referencedColumnName="id")
     */
    private $headerImage;

    /**
     * @var File
     * 
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="background_image_id", referencedColumnName="id")
     */
    private $backgroundImage;

    /**
     * @var File
     * 
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="footer_image_id", referencedColumnName="id")
     */
    private $footerImage;

    /**
     * @var int
     *
     * @ORM\Column(name="font_size", type="integer", nullable=true)
     */
    private $fontSize;

    /**
     * @var string
     *
     * @ORM\Column(name="background_color", type="string", length=20, nullable=true)
     */
    private $backgroundColor;

    /**
     * @var string
     *
     * @ORM\Column(name="font_color", type="string", length=20, nullable=true)
     */
    private $fontColor;

    /**
     * @var int
     *
     * @ORM\Column(name="post_per_line", type="smallint", nullable=true)
     */
    private $postPerLine;

    /**
     * @var int
     *
     * @ORM\Column(name="post_thumbnail_width", type="integer", nullable=true)
     */
    private $postThumbnailWidth;

    /**
     * @var int
     *
     * @ORM\Column(name="space_between_post", type="integer", nullable=true)
     */
    private $spaceBetweenPost;


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
     * Set contest
     *
     * @param Contest $contest
     *
     * @return Template
     */
    public function setContest($contest)
    {
        $this->contest = $contest;

        return $this;
    }

    /**
     * Get contest
     *
     * @return Contest
     */
    public function getContest()
    {
        return $this->contest;
    }

    /**
     * Set headerImage
     *
     * @param string $headerImage
     *
     * @return Template
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;

        return $this;
    }

    /**
     * Get headerImage
     *
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * Set backgroundImage
     *
     * @param string $backgroundImage
     *
     * @return Template
     */
    public function setBackgroundImage($backgroundImage)
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    /**
     * Get backgroundImage
     *
     * @return string
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }

    /**
     * Set footerImage
     *
     * @param string $footerImage
     *
     * @return Template
     */
    public function setFooterImage($footerImage)
    {
        $this->footerImage = $footerImage;

        return $this;
    }

    /**
     * Get footerImage
     *
     * @return string
     */
    public function getFooterImage()
    {
        return $this->footerImage;
    }

    /**
     * Set fontSize
     *
     * @param integer $fontSize
     *
     * @return Template
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * Get fontSize
     *
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     *
     * @return Template
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Get backgroundColor
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Set fontColor
     *
     * @param string $fontColor
     *
     * @return Template
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * Get fontColor
     *
     * @return string
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * Set postPerLine
     *
     * @param integer $postPerLine
     *
     * @return Template
     */
    public function setPostPerLine($postPerLine)
    {
        $this->postPerLine = $postPerLine;

        return $this;
    }

    /**
     * Get postPerLine
     *
     * @return int
     */
    public function getPostPerLine()
    {
        return $this->postPerLine;
    }

    /**
     * Set postThumbnailWidth
     *
     * @param integer $postThumbnailWidth
     *
     * @return Template
     */
    public function setPostThumbnailWidth($postThumbnailWidth)
    {
        $this->postThumbnailWidth = $postThumbnailWidth;

        return $this;
    }

    /**
     * Get postThumbnailWidth
     *
     * @return int
     */
    public function getPostThumbnailWidth()
    {
        return $this->postThumbnailWidth;
    }

    /**
     * Set spaceBetweenPost
     *
     * @param integer $spaceBetweenPost
     *
     * @return Template
     */
    public function setSpaceBetweenPost($spaceBetweenPost)
    {
        $this->spaceBetweenPost = $spaceBetweenPost;

        return $this;
    }

    /**
     * Get spaceBetweenPost
     *
     * @return int
     */
    public function getSpaceBetweenPost()
    {
        return $this->spaceBetweenPost;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Template
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
}

