<?php

namespace ContestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ContestBundle\Entity\Post;
use ContestBundle\Entity\Filetype;
use ContestBundle\Entity\Notification;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Contest
 *
 * @ORM\Table(name="contest")
 * @ORM\Entity(repositoryClass="ContestBundle\Repository\ContestRepository")
 */
class Contest
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    protected $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish_date", type="datetime")
     */
    protected $finishDate;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=255)
     */
    protected $createdBy;

    /**
     * @ORM\ManyToMany(targetEntity="Filetype")
     * @ORM\JoinTable(name="allowed_filetypes",
     *      joinColumns={@ORM\JoinColumn(name="contest_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="filetype_id", referencedColumnName="id")}
     *      )
     */
    protected $allowedFiles;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="is_published",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $isPublished = false;

    /**
     * @var string
     * 
     * @ORM\Column(name="thumbnail", type="string", length=255)
     */
    protected $thumbnail;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Post", mappedBy="contest", cascade={"remove"})
     */
    protected $posts;

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="string", length=255)
     */
    protected $description;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="need_to_accept",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $needToAccept = false;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="comments_allowed",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $commentsAllowed = false;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="auto_publish_new_post",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $autoPublishNewPost = false;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="votes_allowed",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $votesAllowed = false;

    /**
     * @var int
     * 
     * @ORM\Column(
     *     name="post_limit",
     *     type="integer",
     *     nullable=true,
     *     options={"default": 1}
     * )
     */
    protected $postLimit = 0;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="allow_remove",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $allowRemove = false;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="allow_comment_vote",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $allowCommentVote = false;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="send_notifications",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $sendNotifications = false;

    /**
     * @ORM\ManyToMany(targetEntity="Notification")
     * @ORM\JoinTable(name="contest_notifications",
     *      joinColumns={@ORM\JoinColumn(name="contest_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id")}
     *      )
     */
    protected $notifications;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="allow_report",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $allowReport = false;

    /**
     * @var int
     * 
     * @ORM\Column(
     *     name="max_votes_per_user",
     *     type="integer",
     *     nullable=true,
     *     options={"default": 0}
     * )
     */
    protected $maxVotesPerUser = 0;
    
    /**
     * @var int
     * 
     * @ORM\Column(
     *     name="max_file_size",
     *     type="integer",
     *     nullable=true,
     *     options={"default": 0}
     * )
     */
    protected $maxFileSize = 0;
    
   // protected $moderators;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="block_right_mouse",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $blockRightMouse = false;

    /**
     * @var int
     * 
     * @ORM\Column(
     *     name="media_count_limit",
     *     type="integer",
     *     nullable=true,
     *     options={"default": 0}
     * )
     */
    protected $mediaCountLimit = 0;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="count_views",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $countViews = false;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     name="moderated_comments",
     *     type="boolean",
     *     nullable=false,
     *     options={"default": 0}
     * )
     */
    protected $moderatedComments = false;

    public function __construct() {
        $this->allowedFiles = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }
    
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
     * Set title
     *
     * @param string $title
     *
     * @return Contest
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Contest
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set finishDate
     *
     * @param \DateTime $finishDate
     *
     * @return Contest
     */
    public function setFinishDate($finishDate)
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * Get finishDate
     *
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Contest
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Contest
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

    public function addPost(Post $post)
    {
        $this->posts->add($post);
    }

    public function removePost(Post $post)
    {
        $this->posts->remove($post);
    }

    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return bool
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     *
     * @return Contest
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function getNeedToAccept()
    {
        return $this->needToAccept;
    }

    /**
     * @param bool $needToAccept
     *
     * @return Contest
     */
    public function setNeedToAccept($needToAccept)
    {
        $this->needToAccept = $needToAccept;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAutoPublishNewPost()
    {
        return $this->autoPublishNewPost;
    }

    /**
     * @param bool $autoPublishNewPost
     *
     * @return Contest
     */
    public function setAutoPublishNewPost($autoPublishNewPost)
    {
        $this->autoPublishNewPost = $autoPublishNewPost;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCommentsAllowed()
    {
        return $this->commentsAllowed;
    }

    /**
     * @param bool $commentsAllowed
     *
     * @return Contest
     */
    public function setCommentsAllowed($commentsAllowed)
    {
        $this->commentsAllowed = $commentsAllowed;

        return $this;
    }

    /**
     * @return bool
     */
    public function getVotesAllowed()
    {
        return $this->votesAllowed;
    }

    /**
     * @param bool $votesAllowed
     *
     * @return Contest
     */
    public function setVotesAllowed($votesAllowed)
    {
        $this->votesAllowed = $votesAllowed;

        return $this;
    }

    /**
     * @return int
     */
    public function getPostLimit()
    {
        return $this->postLimit;
    }

    /**
     * @param int $postLimit
     *
     * @return Contest
     */
    public function setPostLimit($postLimit)
    {
        $this->postLimit = $postLimit;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowRemove()
    {
        return $this->allowRemove;
    }

    /**
     * @param bool $allowRemove
     *
     * @return Contest
     */
    public function setAllowRemove($allowRemove)
    {
        $this->allowRemove = $allowRemove;

        return $this;
    }

    /**
     * Get the value of allowCommentVote
     *
     * @return  bool
     */ 
    public function getAllowCommentVote()
    {
        return $this->allowCommentVote;
    }

    /**
     * Set the value of allowCommentVote
     *
     * @param  bool  $allowCommentVote
     *
     * @return  self
     */ 
    public function setAllowCommentVote(bool $allowCommentVote)
    {
        $this->allowCommentVote = $allowCommentVote;

        return $this;
    }

    /**
     * Get the value of sendNotifications
     *
     * @return  bool
     */ 
    public function getSendNotifications()
    {
        return $this->sendNotifications;
    }

    /**
     * Set the value of sendNotifications
     *
     * @param  bool  $sendNotifications
     *
     * @return  self
     */ 
    public function setSendNotifications(bool $sendNotifications)
    {
        $this->sendNotifications = $sendNotifications;

        return $this;
    }

    public function getNotifications()
    {
        return $this->notifications;
    }

    public function addNotifications(Notification $notification)
    {
        $this->notifications->add($notification);
    }

    public function getAllowedFiles()
    {
        return $this->allowedFiles;
    }

    public function addAllowedFiles(Filetype $allowedFiles)
    {
        $this->allowedFiles->add($allowedFiles);
    }


    /**
     * Get the value of allowReport
     *
     * @return  bool
     */ 
    public function getAllowReport()
    {
        return $this->allowReport;
    }

    /**
     * Set the value of allowReport
     *
     * @param  bool  $allowReport
     *
     * @return  self
     */ 
    public function setAllowReport(bool $allowReport)
    {
        $this->allowReport = $allowReport;

        return $this;
    }

    /**
     * Get the value of maxVotesPerUser
     *
     * @return  int
     */ 
    public function getMaxVotesPerUser()
    {
        return $this->maxVotesPerUser;
    }

    /**
     * Set the value of maxVotesPerUser
     *
     * @param  int  $maxVotesPerUser
     *
     * @return  self
     */ 
    public function setMaxVotesPerUser(int $maxVotesPerUser)
    {
        $this->maxVotesPerUser = $maxVotesPerUser;

        return $this;
    }

    /**
     * Get the value of allowToReport
     *
     * @return  bool
     */ 
    public function getAllowToReport()
    {
        return $this->allowToReport;
    }

    /**
     * Set the value of allowToReport
     *
     * @param  bool  $allowToReport
     *
     * @return  self
     */ 
    public function setAllowToReport(bool $allowToReport)
    {
        $this->allowToReport = $allowToReport;

        return $this;
    }

    /**
     * Get the value of mediaCountLimit
     *
     * @return  int
     */ 
    public function getMediaCountLimit()
    {
        return $this->mediaCountLimit;
    }

    /**
     * Set the value of mediaCountLimit
     *
     * @param  int  $mediaCountLimit
     *
     * @return  self
     */ 
    public function setMediaCountLimit(int $mediaCountLimit)
    {
        $this->mediaCountLimit = $mediaCountLimit;

        return $this;
    }

    /**
     * Get the value of blockRightMouse
     *
     * @return  bool
     */ 
    public function getBlockRightMouse()
    {
        return $this->blockRightMouse;
    }

    /**
     * Set the value of blockRightMouse
     *
     * @param  bool  $blockRightMouse
     *
     * @return  self
     */ 
    public function setBlockRightMouse(bool $blockRightMouse)
    {
        $this->blockRightMouse = $blockRightMouse;

        return $this;
    }

    /**
     * Get the value of maxFileSize
     *
     * @return  int
     */ 
    public function getMaxFileSize()
    {
        return $this->maxFileSize;
    }

    /**
     * Set the value of maxFileSize
     *
     * @param  int  $maxFileSize
     *
     * @return  self
     */ 
    public function setMaxFileSize(int $maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get the value of countViews
     *
     * @return  bool
     */ 
    public function getCountViews()
    {
        return $this->countViews;
    }

    /**
     * Set the value of countViews
     *
     * @param  bool  $countViews
     *
     * @return  self
     */ 
    public function setCountViews(bool $countViews)
    {
        $this->countViews = $countViews;

        return $this;
    }

    /**
     * Get the value of moderatedComments
     *
     * @return  bool
     */ 
    public function getModeratedComments()
    {
        return $this->moderatedComments;
    }

    /**
     * Set the value of moderatedComments
     *
     * @param  bool  $moderatedComments
     *
     * @return  self
     */ 
    public function setModeratedComments(bool $moderatedComments)
    {
        $this->moderatedComments = $moderatedComments;

        return $this;
    }
}

