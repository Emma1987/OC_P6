<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\AvatarRepository")
 */
class Avatar
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
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    private $file;

    public function upload($file, $username)
    {
        if (null === $file) {
            return;
        }

        $name = 'avatar-'.$username;
        $this->url = 'uploads/avatars/'.$name;

        $file->move($this->getUploadRootDir(), $name);
    }

    public function getUploadDir()
    {
        return 'uploads/avatars';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
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
     * Set url.
     *
     * @param string|null $url
     *
     * @return Avatar
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set file
     * 
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     */
    public function getFile()
    {
        return $this->file;
    }
}
