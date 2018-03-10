<?php
namespace Snowtricks\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Entity\TrickGroup;

class LoadTrick implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $group1 = new TrickGroup();
        $group1->setName('Straight airs');

        $group2 = new TrickGroup();
        $group2->setName('Grabs');

        $group3 = new TrickGroup();
        $group3->setName('Spins');

        $manager->persist($group1);
        $manager->persist($group2);
        $manager->persist($group3);

        $tricks = array(
            array(
                'name' => 'One-Two',
                'description' => 'A trick in which the rider\'s front hand grabs the heel edge behind his back foot.',
                'slug' => 'one-two',
                'published_at' => new \Datetime('2018-02-04 23:00:00'),
                'updated_at' => new \Datetime('2018-03-06 12:12:00'),
                'trickgroup_id' => $group1),
            array(
                'name' => 'A B',
                'description' => 'A trick in which the rider\'s rear hand grabs the heel side of the board front for the front bindings.',
                'slug' => 'a-b',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group2),
            array(
                'name' => 'Beef Carpaccio',
                'description' => 'A Roast Beef and Chicken Salad (in between the legs) at the same time with hands crossed.',
                'slug' => 'beef-carpaccio',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group3),
            array(
                'name' => 'Beef Curtains',
                'description' => 'A Roast Beef and Chicken Salad (in between the legs) at the same time. Also known as The King or Steak Tar Tar',
                'slug' => 'beef-curtains',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group1),
            array(
                'name' => 'Bloody Dracula',
                'description' => 'A trick in which the rider grabs the tail of the board with both hands. The rear hand grabs the board as it would do it during a regular tail-grab but the front hand blindly reaches for the board behind the riders back.',
                'slug' => 'bloddy-dracula',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group2),
            array(
                'name' => 'Canadian Bacon',
                'description' => 'The rear hand reaches behind the rear leg to grab the toe edge between the bindings while the rear leg is boned.',
                'slug' => 'canadian-bacon',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group1),
            array(
                'name' => 'Chicken salad',
                'description' => 'The rear hand reaches between the legs and grabs the heel edge between the bindings while the front leg is boned. The wrist is rotated inward to complete the grab.',
                'slug' => 'chicken-salad',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group1),
            array(
                'name' => 'Crail',
                'description' => 'The rear hand grabs the toe edge in front of the front foot while the rear leg is boned.[1] Alternatively, some consider any rear handed grab in front of the front foot on the toeside edge a crail grab, classifying a grab to the nose a "nose crail" or "real crail".',
                'slug' => 'crail',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group2),
            array(
                'name' => 'Cross-rocket',
                'description' => 'Advanced variation of a Rocket Air, where the arms are crossed in order to grab opposite sides of the nose of the board, while the rear leg is boned straight and the front leg is tucked up.',
                'slug' => 'cross-rocket',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group2),
            array(
                'name' => 'Drunk Driver',
                'description' => 'Similar to a Truck driver, it is a stalefish grab and mute grab performed at the same time.',
                'slug' => 'drunk-driver',
                'published_at' => new \Datetime('2018-03-01 23:00:00'),
                'updated_at' => null,
                'trickgroup_id' => $group3)
        );

        foreach ($tricks as $row) {
            $trick = new Trick();
            $trick->setName($row['name']);
            $trick->setDescription($row['description']);
            $trick->setSlug($row['slug']);
            $trick->setPublishedAt($row['published_at']);
            $trick->setUpdatedAt($row['updated_at']);
            $trick->setTrickGroup($row['trickgroup_id']);

            $manager->persist($trick);
        }

        $manager->flush();
    }
}