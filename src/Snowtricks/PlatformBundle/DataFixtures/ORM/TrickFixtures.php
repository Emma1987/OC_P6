<?php
namespace Snowtricks\PlatformBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Snowtricks\PlatformBundle\Entity\Trick;
use Snowtricks\PlatformBundle\Entity\TrickGroup;
use Snowtricks\PlatformBundle\Entity\Image;
use Snowtricks\PlatformBundle\Entity\User;

class TrickFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // GROUPS
        $straight_airs = new TrickGroup();
        $straight_airs->setName('Straight airs');
        $manager->persist($straight_airs);

        $grabs = new TrickGroup();
        $grabs->setName('Grabs');
        $manager->persist($grabs);

        $spins = new TrickGroup();
        $spins->setName('Spins');
        $manager->persist($spins);

        $flips = new TrickGroup();
        $flips->setName('Flips and inverted rotations');
        $manager->persist($flips);

        $inverted_hand = new TrickGroup();
        $inverted_hand->setName('Inverted hand plants');
        $manager->persist($inverted_hand);

        $slides = new TrickGroup();
        $slides->setName('Slides');
        $manager->persist($slides);

        $stalls = new TrickGroup();
        $stalls->setName('Stalls');
        $manager->persist($stalls);

        $tweaks = new TrickGroup();
        $tweaks->setName('Tweaks and variations');
        $manager->persist($tweaks);

        $miscellaneous = new TrickGroup();
        $miscellaneous->setName('Miscellaneous tricks and identifiers');
        $manager->persist($miscellaneous);

        $others = new TrickGroup();
        $others->setName('Autre');
        $manager->persist($others);

        // IMAGES
        $image1 = new Image();
        $image1->setUrl('uploads/tricks/elguerial-354.jpg');
        $image1->setAlt('Figure snowboard Elguerial');
        $manager->persist($image1);

        $image2 = new Image();
        $image2->setUrl('uploads/tricks/frontside-grab-indy-564.jpg');
        $image2->setAlt('Figure snowboard Frontside grab / Indy');
        $manager->persist($image2);   

        $image3 = new Image();
        $image3->setUrl('uploads/tricks/frontside-misty-87.jpg');
        $image3->setAlt('Figure snowboard Frontside Misty');
        $manager->persist($image3);

        $image4 = new Image();
        $image4->setUrl('uploads/tricks/frontside-misty-657.jpg');
        $image4->setAlt('Figure snowboard Frontside Misty');
        $manager->persist($image4);   

        $image5 = new Image();
        $image5->setUrl('uploads/tricks/killer-stand-324.jpg');
        $image5->setAlt('Figure snowboard Killer Stand');
        $manager->persist($image5);

        $image6 = new Image();
        $image6->setUrl('uploads/tricks/ninety-ninety-123.jpg');
        $image6->setAlt('Figure snowboard Ninety-ninety');
        $manager->persist($image6);   

        $image7 = new Image();
        $image7->setUrl('uploads/tricks/ninety-roll.jpg');
        $image7->setAlt('Figure snowboard Ninety Roll');
        $manager->persist($image7);

        $image8 = new Image();
        $image8->setUrl('uploads/tricks/poke-32.jpg');
        $image8->setAlt('Figure snowboard Poke');
        $manager->persist($image8);

        $image9 = new Image();
        $image9->setUrl('uploads/tricks/poke-43.jpeg');
        $image9->setAlt('Figure snowboard Poke');
        $manager->persist($image9); 

        $image10 = new Image();
        $image10->setUrl('uploads/tricks/tamedog-421.jpg');
        $image10->setAlt('Figure snowboard Tamedog');
        $manager->persist($image10);

        //USER
        $user = new User();
        $user->setUsername('Administrateur');
        $user->setEmail('email@example.com');
        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);
        $user->setIsActive(true);
        $manager->persist($user);


        // NINETY-NINETY
        $ninetyNinety = new Trick();
        $ninetyNinety->setName('Ninety-ninety');
        $ninetyNinety->setDescription("Un tour aérien dans lequel le snowboarder tord son corps afin de déplacer ou de faire pivoter sa planche d'environ 90° par rapport à sa position normale, puis remet la planche dans sa position d'origine avant d'atterrir. Cette astuce peut être effectuée en avant ou en arrière, et également en variation avec d'autres tricks.");
        $ninetyNinety->setSlug('ninety-ninety');
        $ninetyNinety->setPublishedAt(new \Datetime('2018-04-14 12:08:43'));
        $ninetyNinety->setUpdatedAt(new \Datetime('2018-04-15 12:12:00'));
        $ninetyNinety->setTrickGroup($straight_airs);
        $ninetyNinety->addImage($image6);
        $ninetyNinety->setUser($user);
        $manager->persist($ninetyNinety);

        // FRONTSIDE GRAB
        $frontsideGrab = new Trick();
        $frontsideGrab->setName('Frontside grab / Indy');
        $frontsideGrab->setDescription("Une astuce fondamentale exécutée en saisissant le bord de l'orteil entre les liaisons avec la main qui traîne. Cette astuce est désignée comme une saisie frontside sur un air droit, ou tout en effectuant un spin frontside. Lorsque vous effectuez une rotation arrière ou aérienne, cette saisie est appelée Indy. L'air de frontside a été popularisé par le skateur Tony Alva.");
        $frontsideGrab->setSlug('frontside-grab-indy');
        $frontsideGrab->setPublishedAt(new \Datetime('2018-04-11 08:41:10'));
        $frontsideGrab->setUpdatedAt(null);
        $frontsideGrab->setTrickGroup($grabs);
        $frontsideGrab->addImage($image2);
        $frontsideGrab->setUser($user);
        $manager->persist($frontsideGrab);

        // CHICKEN SALAD
        $chickenSalad = new Trick();
        $chickenSalad->setName('Chicken Salad');
        $chickenSalad->setDescription("La main arrière se met entre les jambes et attrape le bord du talon entre les fixations tandis que la jambe avant est désossée. Le poignet est tourné vers l'intérieur pour compléter la figure.");
        $chickenSalad->setSlug('chicken-salad');
        $chickenSalad->setPublishedAt(new \Datetime('2018-04-10 06:56:59'));
        $chickenSalad->setUpdatedAt(null);
        $chickenSalad->setTrickGroup($grabs);
        $chickenSalad->setUser($user);
        $manager->persist($chickenSalad);

        //LIEN AIR
        $lienAir = new Trick();
        $lienAir->setName('Lien air');
        $lienAir->setDescription("Lors d'une transition frontale, le snowboarder attrape la talonnière devant ou derrière la reliure de tête avec sa main de tête. Pour que ce soit un Lien Air, le tableau ne peut pas être modifié et doit être maintenu à plat. L'origine du nom de l'astuce est l'orthographe inversée du prénom du skateboard Neil Blender.");
        $lienAir->setSlug('lien-air');
        $lienAir->setPublishedAt(new \Datetime('2018-04-01 12:32:53'));
        $lienAir->setUpdatedAt(new \Datetime('2018-04-01 12:43:13'));
        $lienAir->setTrickGroup($grabs);
        $lienAir->setUser($user);
        $manager->persist($lienAir);

        // TAMEDOG
        $tamedog = new Trick();
        $tamedog->setName('Tamedog');
        $tamedog->setDescription("Un frontflip exécuté sur un saut en ligne droite, avec un axe de rotation dans lequel le snowboarder bascule vers l'avant, comme une roue.");
        $tamedog->setSlug('tamedog');
        $tamedog->setPublishedAt(new \Datetime('2018-03-28 04:01:37'));
        $tamedog->setUpdatedAt(null);
        $tamedog->setTrickGroup($flips);
        $tamedog->addImage($image10);
        $tamedog->setUser($user);
        $manager->persist($tamedog);

        // HAAKON FLIP
        $haakon = new Trick();
        $haakon->setName('Haakon flip');
        $haakon->setDescription("Une manœuvre aérienne effectuée dans une demi-lune en décollant en arrière, et en effectuant une rotation inversée de 720°. La rotation imite une demi-cabine menant à McTwist, et porte le nom de la légende norévgienne du freestyle Terje Haakonsen.");
        $haakon->setSlug('haakon-flip');
        $haakon->setPublishedAt(new \Datetime('2018-03-28 19:32:37'));
        $haakon->setUpdatedAt(null);
        $haakon->setTrickGroup($flips);
        $haakon->setUser($user);
        $manager->persist($haakon);

        // FRONTSIDE MISTY
        $frontsideMitsy = new Trick();
        $frontsideMitsy->setName('Frontside Misty');
        $frontsideMitsy->setDescription("La figure Frontside Misty finit par ressembler à un rodéo de frontside au milieu du tour, mais au décollage le snowboarder utilise un type de mouvement frontflip plus pour commencer le tour. Le frontside Misty ne peut être fait que sur les orteils et le snowboarder se tournant vers l'avant, puis enclenchez leur épaule de fuite vers leur pied avant et l'épaule de plomb va se libérer vers le ciel. comme ils se déroulent au décollage. Habituellement en saisissant Indy le snowboarder suit l'épaule de plomb à travers la rotation à 540, 720 et même 900°.");
        $frontsideMitsy->setSlug('frontside-misty');
        $frontsideMitsy->setPublishedAt(new \Datetime('2018-03-27 15:14:40'));
        $frontsideMitsy->setUpdatedAt(null);
        $frontsideMitsy->setTrickGroup($flips);
        $frontsideMitsy->addImage($image3);
        $frontsideMitsy->addImage($image4);
        $frontsideMitsy->setUser($user);
        $manager->persist($frontsideMitsy);

        // NINETY ROLL
        $ninetyRoll = new Trick();
        $ninetyRoll->setName('Ninety Roll');
        $ninetyRoll->setDescription("Une figure effectuée en retournant vers l'atterrissage d'un saut, avec une rotation totale de 180° arrière (c'est-à-dire spin 90° backside-backflip-spin 90°), donc atterrissage fakie. Essentiellement, il s'agit d'un backflip backside 180. Cette astuce est parfois confondue avec un Rodeo arrière, bien que le Ninety Roll ait un axe de rotation beaucoup plus linéaire.");
        $ninetyRoll->setSlug('ninety-roll');
        $ninetyRoll->setPublishedAt(new \Datetime('2018-03-23 17:02:04'));
        $ninetyRoll->setUpdatedAt(new \Datetime('2018-03-23 18:46:04'));
        $ninetyRoll->setTrickGroup($flips);
        $ninetyRoll->addImage($image7);
        $ninetyRoll->setUser($user);
        $manager->persist($ninetyRoll);

        // ELGUERIAL
        $elguerial = new Trick();
        $elguerial->setName('Elguerial');
        $elguerial->setDescription("Un retournement où le mur de halfpipe est approché fakie, la main arrière est plantée, une rotation arrière de 360 degrés est faite, et le cavalier atterrit en avant. Nommé d'après Eddie Elguera.");
        $elguerial->setSlug('elguerial');
        $elguerial->setPublishedAt(new \Datetime('2018-03-23 17:23:51'));
        $elguerial->setUpdatedAt(null);
        $elguerial->setTrickGroup($flips);
        $elguerial->addImage($image1);
        $elguerial->setUser($user);
        $manager->persist($elguerial);

        // KILLER STAND
        $killerStand = new Trick();
        $killerStand->setName('Killer Stand');
        $killerStand->setDescription("Vous faites une inversion mais vous prenez aussi votre main arrière / arrière sur le coude de la main avant.");
        $killerStand->setSlug('killer-stand');
        $killerStand->setPublishedAt(new \Datetime('2018-03-23 17:23:51'));
        $killerStand->setUpdatedAt(null);
        $killerStand->setTrickGroup($inverted_hand);
        $killerStand->addImage($image5);
        $killerStand->setUser($user);
        $manager->persist($killerStand);

        // BLUNTSLIDE
        $bluntslide = new Trick();
        $bluntslide->setName('Bluntslide');
        $bluntslide->setDescription("Une glissade est exécutée lorsque le pied avant du snowboarder passe au-dessus du rail à l'approche, avec son snowboard qui se déplace perpendiculairement et son pied qui traîne directement au-dessus du rail ou de tout autre obstacle (comme une glissade). Lors de l'exécution d'un bluntslide frontside, le snowboarder fait face à la montée. Lors de l'exécution d'un bluntslide arrière, le snowboarder fait face à la descente.");
        $bluntslide->setSlug('bluntslide');
        $bluntslide->setPublishedAt(new \Datetime('2018-03-21 09:23:51'));
        $bluntslide->setUpdatedAt(new \Datetime('2018-03-21 09:46:51'));
        $bluntslide->setTrickGroup($inverted_hand);
        $bluntslide->setUser($user);
        $manager->persist($bluntslide);

        // THE GUTTERBALL
        $gutterball = new Trick();
        $gutterball->setName('The Gutterball');
        $gutterball->setDescription("Le Gutterball est un pied (le pied avant est attaché et le pied arrière est détendu) avec un grip de ceinture de sécurité, ressemblant à la position du corps que quelqu'un aurait après avoir lancé une boule de bowling sur un allié de bowling. Cette astuce a été inventée et nommée par Jeremy Cameron qui lui a valu une première place dans le concours \"FAME WAR\" du Best Trick de Morrow Snowboards en 2009.");
        $gutterball->setSlug('the-gutterball');
        $gutterball->setPublishedAt(new \Datetime('2018-03-20 11:12:11'));
        $gutterball->setUpdatedAt(null);
        $gutterball->setTrickGroup($slides);
        $gutterball->setUser($user);
        $manager->persist($gutterball);

        // BLUNT STALL
        $bluntStall = new Trick();
        $bluntStall->setName('Blunt-stall');
        $bluntStall->setDescription("Imitant la planche à roulettes, et semblable à une planche-stalle, cette astuce est effectuée en calant sur un objet avec la queue de la planche (décrochage émoussé), ou le nez de la planche (nez bouché décrochage). Se distinguant d'un décrochage ou d'un décrochage de la queue car, pendant le décrochage, la majeure partie du snowboard sera positionnée au-dessus de l'obstacle et du point de contact.");
        $bluntStall->setSlug('blunt-stall');
        $bluntStall->setPublishedAt(new \Datetime('2018-03-13 13:12:11'));
        $bluntStall->setUpdatedAt(null);
        $bluntStall->setTrickGroup($stalls);
        $bluntStall->setUser($user);
        $manager->persist($bluntStall);

        // TAIL BLOCK
        $tailBlock = new Trick();
        $tailBlock->setName('Tail-block');
        $tailBlock->setDescription("Une astuce généralement effectuée sur la neige au sommet d'une transition, ou occasionnellement sur un objet, dans lequel le snowboardeur se lève et se dresse sur la queue de son plateau tout en attrapant le nez du plateau.");
        $tailBlock->setSlug('tail-block');
        $tailBlock->setPublishedAt(new \Datetime('2018-03-11 22:12:56'));
        $tailBlock->setUpdatedAt(null);
        $tailBlock->setTrickGroup($stalls);
        $tailBlock->setUser($user);
        $manager->persist($tailBlock);

        // ONE FOOTED
        $oneFooted = new Trick();
        $oneFooted->setName('One-footed');
        $oneFooted->setDescription("Les astuces exécutées avec un pied retiré de la fixation (typiquement le pied arrière) sont appelées astuces à un pied. Les astuces à un pied incluent des plantes rapides dans lesquelles le pied arrière est tombé et initie un air droit ou une rotation, l'os désossé, qui est une plante rapide avec un grappin; et le non-respect, qui est une plante rapide à l'avant-pied.");
        $oneFooted->setSlug('one-footed');
        $oneFooted->setPublishedAt(new \Datetime('2018-03-09 23:02:56'));
        $oneFooted->setUpdatedAt(null);
        $oneFooted->setTrickGroup($tweaks);
        $oneFooted->setUser($user);
        $manager->persist($oneFooted);

        // POKE
        $poke = new Trick();
        $poke->setName('Poke');
        $poke->setDescription("Un tour de passe-passe dans lequel la jambe avant ou la jambe arrière seulement est désossée.");
        $poke->setSlug('poke');
        $poke->setPublishedAt(new \Datetime('2018-03-09 23:02:56'));
        $poke->setUpdatedAt(null);
        $poke->setTrickGroup($tweaks);
        $poke->addImage($image8);
        $poke->addImage($image9);
        $poke->setUser($user);
        $manager->persist($poke);

        // REVERT
        $revert = new Trick();
        $revert->setName('Revert');
        $revert->setDescription("Pour continuer à tourner sur la neige après avoir atterri un saut dans lequel un tour de passe-passe a été effectué. Cela se produit généralement involontairement lorsque le snowboardeur ne peut pas arrêter de tourner alors qu'il atterrit son tour. Alternativement, ce terme peut se référer à un «retour» à la position de conduite après avoir effectué une astuce au beurre ou au rail dans laquelle une rotation a été effectuée. Dans ce cas, c'est souvent l'inversion d'une rotation préalable partielle qui ramène le snowboarder à sa position d'origine.");
        $revert->setSlug('revert');
        $revert->setPublishedAt(new \Datetime('2018-03-02 12:46:47'));
        $revert->setUpdatedAt(new \Datetime('2018-03-02 12:58:03'));
        $revert->setTrickGroup($miscellaneous); 
        $revert->setUser($user);
        $manager->persist($revert);  

        $manager->flush();
    }
}