<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\TrickRepository")
 */
class Trick
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     * @Assert\Length(
     *     min = 3, 
     *     minMessage = "Le nom de la figure doit contenir au minimum 3 caractères", 
     *     max = 100, 
     *     maxMessage = "Le nom de la figure doit contenir au maximum 100 caractères")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\Length(
     *     min = 10, 
     *     minMessage = "La description doit contenir au minimum 10 caractères", 
     *     max = 255, 
     *     maxMessage = "La description doit contenir au maximum 255 caractères")
     */
    private $description;

    /**
     * @var datetime
     *
     * @ORM\Column(name="published_at", type="datetime")
     * @Assert\DateTime()
     */
    private $publishedAt;

    /**
     * @var datetime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Snowtricks\PlatformBundle\Entity\TrickGroup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trickgroup;

    /**
     * @var Image[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Snowtricks\PlatformBundle\Entity\Image", mappedBy="trick", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @var Video[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Snowtricks\PlatformBundle\Entity\Video", cascade={"persist", "remove"})
     */
    private $videos;

    /**
     * @var Message[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Snowtricks\PlatformBundle\Entity\Message", inversedBy="trick", cascade={"persist", "remove"})
     */
    private $messages;

    public function __construct()
    {
        $this->publishedAt = new \Datetime();
        $this->images = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function createSlug($slug)
    {
        $slug = mb_strtolower($slug,'UTF-8');
        $slug = \Normalizer::normalize($slug, \Normalizer::NFC);

        setlocale(LC_CTYPE, 'fr_FR');
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);

        $slug = strip_tags($slug);
        
        $slug = trim(preg_replace('#[^a-z0-9-]+#i', '-', $slug), '-');

        $search = array('#-{2,}#i');
        $slug = preg_replace($search, '-', $slug);

        return $slug;
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
     * Set name
     *
     * @param string $name
     *
     * @return Trick
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set description
     *
     * @param string $description
     *
     * @return Trick
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Trick
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
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Trick
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Trick
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set trickgroup
     *
     * @param \Snowtricks\PlatformBundle\Entity\TrickGroup $trickgroup
     *
     * @return Trick
     */
    public function setTrickgroup(TrickGroup $trickgroup)
    {
        $this->trickgroup = $trickgroup;

        return $this;
    }

    /**
     * Get trickgroup
     *
     * @return \Snowtricks\PlatformBundle\Entity\TrickGroup
     */
    public function getTrickgroup()
    {
        return $this->trickgroup;
    }


    /**
     * Add image
     *
     * @param \Snowtricks\PlatformBundle\Entity\Image $image
     *
     * @return Trick
     */
    public function addImage(Image $image)
    {
        $this->images->add($image);
        $image->setTrick($this);

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Snowtricks\PlatformBundle\Entity\Image $image
     */
    public function removeImage(\Snowtricks\PlatformBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add message.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Message $message
     *
     * @return Trick
     */
    public function addMessage(\Snowtricks\PlatformBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Message $message
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMessage(\Snowtricks\PlatformBundle\Entity\Message $message)
    {
        return $this->messages->removeElement($message);
    }

    /**
     * Get messages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add video.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Video $video
     *
     * @return Trick
     */
    public function addVideo(\Snowtricks\PlatformBundle\Entity\Video $video)
    {
        $this->videos[] = $video;

        return $this;
    }

    /**
     * Remove video.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Video $video
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeVideo(\Snowtricks\PlatformBundle\Entity\Video $video)
    {
        return $this->videos->removeElement($video);
    }

    /**
     * Get videos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
