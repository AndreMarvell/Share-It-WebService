<?php

namespace Covoiturage\RateCommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Comment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="Covoiturage\RateCommentBundle\Entity\CommentThread")
     */
    protected $thread;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Covoiturage\UserBundle\Entity\Users")
     * @var Users
     */
    protected $commenter;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;
    
    
    /**
     * Constructor
     */
    function __construct(CommentThread $thread, \Covoiturage\UserBundle\Entity\Users $commenter) {
        $this->thread = $thread;
        $this->commenter = $commenter;
    }
    
    
    /**
     * @ORM\prePersist
     */
    public function increase()
    {
        $this->thread->incrementNumComment();
    }

    /**
     * @ORM\preRemove
     */
    public function decrease()
    {
      $this->thread->decrementNumComment();
    }

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
     * Set thread
     *
     * @param \Covoiturage\RateCommentBundle\Entity\CommentThread $thread
     * @return Comment
     */
    public function setThread(\Covoiturage\RateCommentBundle\Entity\CommentThread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \Covoiturage\RateCommentBundle\Entity\CommentThread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set commenter
     *
     * @param \Covoiturage\UserBundle\Entity\Users $commenter
     * @return Commenter
     */
    public function setCommenter(\Covoiturage\UserBundle\Entity\Users $commenter = null)
    {
        $this->commenter = $commenter;

        return $this;
    }

    /**
     * Get commenter
     *
     * @return \Covoiturage\UserBundle\Entity\Users 
     */
    public function getCommenter()
    {
        return $this->commenter;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }
}
