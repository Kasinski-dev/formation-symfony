<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // Nous gérons les rôles
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Seb')
                  ->setLastName('Kasinski')
                  ->setEmail('seb.kasinski@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('/dragon.png')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // http://127.0.0.1:8000/dragon.png

        // Nour gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++) {     
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/women/90.jpg';
            // $picture = 'https://randomuser.me/api/portraits/';
            // $pictureId = $faker->number(1, 99) . ".jpg";

            // $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gérons les annonces
        for ($i = 1; $i <= 30; $i++) 
        {
            $ad = new Ad();
            
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $coverImage = str_replace("https://lorempixel.com", "https://picsum.photos", $coverImage);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) -1)];


            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
           

            for($j = 1; $j <= mt_rand(2, 5); $j++){
                $lienImage = $faker->imageUrl();
                $lienImage = str_replace("https://lorempixel.com", "https://picsum.photos", $lienImage);
                
                //dd($lienImage);
               
                $image = new Image();
                $image->setUrl($lienImage)
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }
            
            // Gestion des réservations 
            for ($j=1; $j < mt_rand(0, 10); $j++) { 
                $booking = new Booking();

                $createdAt  = $faker->dateTimeBetween('-6 months');
                $startDate  = $faker->dateTimeBetween('-3 months');

                // Gestion de la date de fin
                $duration   = mt_rand(3, 10);
                $endDate    = (clone $startDate)->modify("+$duration days");
                
                $amount     = $ad->getPrice() * $duration;
                $booker     = $users[mt_rand(0, count($users) - 1)];
                $comment    = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad) 
                        ->setStartDate($startDate) 
                        ->setEndDate($endDate) 
                        ->setCreatedAt($createdAt) 
                        ->setAmount($amount) 
                        ->setComment($comment); 

                $manager->persist($booking);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
