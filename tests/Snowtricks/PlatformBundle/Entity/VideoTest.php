<?php

namespace Tests\Snowtricks\PlatformBundle\Entity;

use Snowtricks\PlatformBundle\Entity\Video;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testCreateIframeUrlFromYoutube()
    {
    	$video = new Video();
    	$video->setUrl('https://youtu.be/rfGtrFdCv32zSRr');
    	$video->getPlatformFromUrl();
    	$iframeUrl = $video->getIframeUrl();

        $this->expectOutputString('https://www.youtube.com/embed/rfGtrFdCv32zSRr');
        print($iframeUrl);
    }

    public function testCreateIframeUrlFromDailymotion()
    {
    	$video = new Video();
    	$video->setUrl('https://dai.ly/rfGtrFdCv32zSRr');
    	$video->getPlatformFromUrl();
    	$iframeUrl = $video->getIframeUrl();

        $this->expectOutputString('//www.dailymotion.com/embed/video/rfGtrFdCv32zSRr');
        print($iframeUrl);
    }
}