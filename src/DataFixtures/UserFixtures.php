<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user-fixture@gmail.com');
        $user->setRoles(['ADMIN_ROLE']);
        $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        '0000'
        ));
        $manager->persist($user);

        $manager->flush();
    }
}
