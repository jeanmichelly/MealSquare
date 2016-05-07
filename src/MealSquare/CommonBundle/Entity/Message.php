<?php

namespace MealSquare\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table("message")
 * @ORM\Entity
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_state", type="string", length=1, options={"fixed" = true})
     */
    private $senderState = "1";

    /**
     * @var string
     *
     * @ORM\Column(name="receiver_state", type="string", length=1, options={"fixed" = true})
     */
    private $receiverState = "1";

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedUser;

    function __construct($user, $relatedUser, $subject, $content) {
        $this->user = $user;
        $this->relatedUser = $relatedUser;
        $this->date = new \Datetime();
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Message
     */
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set senderState
     *
     * @param string $senderState
     * @return Message
     */
    public function setSenderState($senderState) {
        $this->senderState = $senderState;
        return $this;
    }

    /**
     * Get senderState
     *
     * @return string 
     */
    public function getSenderState() {
        return $this->senderState;
    }

    /**
     * Set receiverState
     *
     * @param string $receiverState
     * @return Message
     */
    public function setReceiverState($receiverState) {
        $this->receiverState = $receiverState;
        return $this;
    }

    /**
     * Get receiverState
     *
     * @return string 
     */
    public function getReceiverState() {
        return $this->receiverState;
    }

    /**
     * Set user
     *
     * @param \CV\UserBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\CV\UserBundle\Entity\User $user) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \CV\UserBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set relatedUser
     *
     * @param \CV\UserBundle\Entity\User $user
     * @return Message
     */
    public function setRelatedUser(\CV\UserBundle\Entity\User $relatedUser) {
        $this->relatedUser = $relatedUser;
        return $this;
    }

    /**
     * Get relatedUser
     *
     * @return \CV\UserBundle\Entity\User 
     */
    public function getRelatedUser() {
        return $this->relatedUser;
    }
}
