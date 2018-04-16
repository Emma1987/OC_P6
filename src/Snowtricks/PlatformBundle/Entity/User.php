<?php

namespace Snowtricks\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Snowtricks\PlatformBundle\Repository\UserRepository")
 * @UniqueEntity(
 *     fields="email", 
 *     message="Un compte existe déjà avec cet email.", 
 *     groups={"register"})
 * @UniqueEntity(
 *     fields="username", 
 *     message="Ce nom d'utilisateur est déjà pris.", 
 *     groups={"register"})
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=50, unique=true)
     * @Assert\NotBlank(groups={"register", "resetPassDemand"})
     * @Assert\Length(
     *     min = 3, 
     *     minMessage = "Votre nom d'utilisateur doit contenir au minimum 3 caractères", 
     *     max = 100, 
     *     maxMessage = "Votre nom d'utilisateur doit contenir au maximum 100 caractères",
     *     groups={"register", "resetPassDemand"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @Assert\NotBlank(groups={"register", "resetPassAction"})
     * @Assert\Email(groups={"register", "resetPassAction"})
     */
    private $email;

    /**
     * @Assert\NotBlank(groups={"register", "resetPassAction"})
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Le mot de passe doit être d'au minimum 6 caractères",
     *     max = 4096,
     *     groups={"register", "resetPassAction"})
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var Avatar
     *
     * @ORM\OneToOne(targetEntity="Snowtricks\PlatformBundle\Entity\Avatar", cascade={"persist", "remove"})
     */
    private $avatar;

    public function __construct()
    {
        $this->isActive = false;
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
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set plain password.
     *
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plain password.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ) = unserialize($serialized);
    }

    /**
     * Set token.
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set avatar.
     *
     * @param \Snowtricks\PlatformBundle\Entity\Avatar|null $avatar
     *
     * @return User
     */
    public function setAvatar(\Snowtricks\PlatformBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return \Snowtricks\PlatformBundle\Entity\Avatar|null
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
