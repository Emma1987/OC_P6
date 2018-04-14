<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="url", type="string", length=120, unique=true)
     * @Assert\Type("string")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=120)
     * @Assert\Type("string")
     */
    private $alt;

    /**
     * @var Trick
     *
     * @ORM\ManyToOne(targetEntity="Snowtricks\PlatformBundle\Entity\Trick", inversedBy="images")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $trick;

    /**
     * @Assert\Image(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Le poids de l'image doit être inférieur à 1Mo",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "L'image doit être au format png, jpg ou jpeg.")
     */
    public $files;

    /**
     * Upload the image 
     */
    public function upload($file, Trick $trick)
    {
        if (null === $file) {
            return;
        }

        $name = $trick->getSlug().'-'.random_int(1, 10000);
        $this->url = 'uploads/tricks/'.$name;
        $this->alt = 'Figure snowboard'.' '.$trick->getName();

        $file->move($this->getUploadRootDir(), $name);
    }

    public function getUploadDir()
    {
        return 'uploads/tricks';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
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
     * Set file
     * 
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->files = new ArrayCollection($file);

        return $this;
    }

    /**
     * Get file
     */
    public function getFiles()
    {
        return $this->files;
    }
}
