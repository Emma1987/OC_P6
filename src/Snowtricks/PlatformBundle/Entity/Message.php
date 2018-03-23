<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="message_published", type="datetime")
     */
    private $messagePublished;

    /**
     * @var Trick
     * @ORM\ManyToMany(targetEntity="Snowtricks\PlatformBundle\Entity\Trick", mappedBy="messages")
     */
    private $trick;

    public function __construct()
    {
        $this->messagePublished = new \Datetime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Message
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set messagePublished.
     *
     * @param \DateTime $messagePublished
     *
     * @return Message
     */
    public function setMessagePublished($messagePublished)
    {
        $this->messagePublished = $messagePublished;

        return $this;
    }

    /**
     * Get messagePublished.
     *
     * @return \DateTime
     */
    public function getMessagePublished()
    {
        return $this->messagePublished;
    }

    /**
     * @return Trick
     */
    public function getTrick()
    {
        return $this->trick;
    }
}
