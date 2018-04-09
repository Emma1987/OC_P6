<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="content", type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Le message doit être inférieur à 255 caractères")
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

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Snowtricks\PlatformBundle\Entity\User")
     */
    private $user;

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

    /**
     * Add trick.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Trick $trick
     *
     * @return Message
     */
    public function addTrick(\Snowtricks\PlatformBundle\Entity\Trick $trick)
    {
        $this->trick[] = $trick;

        return $this;
    }

    /**
     * Remove trick.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Trick $trick
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTrick(\Snowtricks\PlatformBundle\Entity\Trick $trick)
    {
        return $this->trick->removeElement($trick);
    }

    /**
     * Set user.
     *
     * @param \Snowtricks\PlatformBundle\Entity\User|null $user
     *
     * @return Message
     */
    public function setUser(\Snowtricks\PlatformBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Snowtricks\PlatformBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
