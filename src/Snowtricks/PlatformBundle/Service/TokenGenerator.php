<?php

namespace Snowtricks\PlatformBundle\Service;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Token Generator
 */
class TokenGenerator
{
    /**
     * @var string
     */
    protected $alphabet;

    /**
     * @var int
     * @Assert\Type(type="int", message="La longueur du token doit être un nombre.")
     * @Assert\Length(max="100", maxMessage="Le token ne doit pas dépasser 100 caractères.")
     * @Assert\NotNull(message="La longueur du token ne peut être nulle.")
     */
    protected $tokenLength;

    public function __construct()
    {
        $this->setAlphabet(
            implode(range('a', 'z'))
            . implode(range('A', 'Z'))
            . implode(range(0, 9))
        );
    }

    /**
     * Generate the token
     * @param  int $tokenLength
     * @return string $token
     */
    public function generateToken($tokenLength)
    {
        return $token = substr(str_shuffle(str_repeat($this->alphabet, $tokenLength)), 0, $tokenLength);
    }

    /**
     * Get alphabet
     */
    public function getAlphabet()
    {
        return $this->alphabet;
    }

    /**
     * Get token length
     */
    public function getTokenLength()
    {
        return $this->tokenLength;
    }

    /**
     * Set alphabet
     */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
    }

    /**
     * Set token length
     */
    public function setTokenLength($tokenLength)
    {
        $this->tokenLength = $tokenLength;
    }
}