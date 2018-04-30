<?php

namespace Tests\Snowtricks\PlatformBundle\Service;

use Snowtricks\PlatformBundle\Service\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    public function testGenerateToken()
    {
    	$token = new TokenGenerator();

    	$result = $token->generateToken(40);

        $this->expectOutputRegex('#[a-zA-Z0-9]{40}#');
        print($result);
    }
}