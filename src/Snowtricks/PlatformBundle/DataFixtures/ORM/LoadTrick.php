<?php
namespace Snowtricks\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Snowtricks\PlatformBundle\Entity\Trick;

class LoadTrick implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tricks = array(
            array(
                'name' => 'One-Two',
                'description' => 'A trick in which the rider\'s front hand grabs the heel edge behind his back foot.',
                'slug' => 'One-Two'),
            array(
                'name' => 'A B',
                'description' => 'A trick in which the rider\'s rear hand grabs the heel side of the board front for the front bindings.',
                'slug' => 'A-B'),
            array(
                'name' => 'Beef Carpaccio ',
                'description' => 'A Roast Beef and Chicken Salad (in between the legs) at the same time with hands crossed.',
                'slug' => 'Beef-Carpaccio '),
            array(
                'name' => 'Beef Curtains',
                'description' => 'A Roast Beef and Chicken Salad (in between the legs) at the same time. Also known as The King or Steak Tar Tar',
                'slug' => 'Beef-Curtains'),
            array(
                'name' => 'Bloody Dracula',
                'description' => 'A trick in which the rider grabs the tail of the board with both hands. The rear hand grabs the board as it would do it during a regular tail-grab but the front hand blindly reaches for the board behind the riders back.',
                'slug' => 'Bloody-Dracula'),
            array(
                'name' => 'Canadian Bacon',
                'description' => 'The rear hand reaches behind the rear leg to grab the toe edge between the bindings while the rear leg is boned.',
                'slug' => 'Canadian-Bacon'),
            array(
                'name' => 'Chicken salad',
                'description' => 'The rear hand reaches between the legs and grabs the heel edge between the bindings while the front leg is boned. The wrist is rotated inward to complete the grab.',
                'slug' => 'Chicken-salad'),
            array(
                'name' => 'Crail',
                'description' => 'The rear hand grabs the toe edge in front of the front foot while the rear leg is boned.[1] Alternatively, some consider any rear handed grab in front of the front foot on the toeside edge a crail grab, classifying a grab to the nose a "nose crail" or "real crail".',
                'slug' => 'Crail'),
            array(
                'name' => 'Cross-rocket',
                'description' => 'Advanced variation of a Rocket Air, where the arms are crossed in order to grab opposite sides of the nose of the board, while the rear leg is boned straight and the front leg is tucked up.',
                'slug' => 'Cross-rocket'),
            array(
                'name' => 'Drunk Driver',
                'description' => 'Similar to a Truck driver, it is a stalefish grab and mute grab performed at the same time.',
                'slug' => 'Drunk-Driver')
        );

        foreach ($tricks as $row) {
            $trick = new Trick();
            $trick->setName($row['name']);
            $trick->setDescription($row['description']);
            $trick->setSlug($row['slug']);

            $manager->persist($trick);
        }

        $manager->flush();
    }
}