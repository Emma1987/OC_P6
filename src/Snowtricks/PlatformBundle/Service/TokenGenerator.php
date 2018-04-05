<?php

namespace Snowtricks\PlatformBundle\Service;

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