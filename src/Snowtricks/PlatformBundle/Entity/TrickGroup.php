<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TrickGroup
 *
 * @ORM\Table(name="trick_group")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\TrickGroupRepository")
 */
class TrickGroup
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
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     * @Assert\Type("string")
     * @Assert\Length(
     *     min = 3, 
     *     minMessage = "Le nom du groupe doit contenir au minimum 3 caractères", 
     *     max = 50, 
     *     maxMessage = "Le nom du groupe doit contenir au maximum 50 caractères")
     */
    private $name;


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
     * @return TrickGroup
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
}