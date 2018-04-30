<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Video
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
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\Regex(
     *     pattern="#^(http|https)://(youtu.be/|dai.ly/).*#",
     *     match=true,
     *     message="L'url ne semble pas être une URL de partage provenant de Youtube ou Dailymotion.")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="iframe_url", type="string", length=255)
     */
    private $iframeUrl;

    /**
     * @var Trick
     *
     * @ORM\ManyToOne(targetEntity="Snowtricks\PlatformBundle\Entity\Trick", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $trick;


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
     * Set url.
     *
     * @param string $url
     *
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set iframeUrl.
     *
     * @param string $iframeUrl
     *
     * @return Video
     */
    public function setIframeUrl($iframeUrl)
    {
        $this->iframeUrl = $iframeUrl;

        return $this;
    }

    /**
     * Get iframeUrl.
     *
     * @return string
     */
    public function getIframeUrl()
    {
        return $this->iframeUrl;
    }

    /**
     * Set trick
     *
     * @param \Snowtricks\PlatformBundle\Entity\Trick $trick
     *
     * @return Image
     */
    public function setTrick(\Snowtricks\PlatformBundle\Entity\Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * Get trick
     *
     * @return \Snowtricks\PlatformBundle\Entity\Trick
     */
    public function getTrick()
    {
        return $this->trick;
    }

    /**
     * Get the platform where the video comes from
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @ORM\PreFlush
     */
    public function getPlatformFromUrl()
    {
        if (preg_match('#^(http|https)://youtu.be/#', $this->url)) {
            $this->fromYoutube($this->url);
        }
        elseif (preg_match('#^(http|https)://dai.ly/#', $this->url)) {
            $this->fromDailymotion($this->url);
        }
    }

    /**
     * Defines the Youtube iframe Url
     */
    private function fromYoutube($url)
    {
        $pos = strpos($url, 'be/');
        $videoCode = substr($url, $pos+3);
        $newUrl = 'https://www.youtube.com/embed/'.$videoCode;

        $this->setIframeUrl($newUrl);    
    }

    /**
     * Defines the Dailymotion iframe Url
     */
    private function fromDailymotion($url)
    {
        $pos = strpos($url, 'ly/');
        $videoCode = substr($url, $pos+3);
        $newUrl = '//www.dailymotion.com/embed/video/'.$videoCode;

        $this->setIframeUrl($newUrl);    
    }
}
