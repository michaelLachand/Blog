<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $user = new User();
       $user->setUsername('Mike');
       $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$VGR2Q0x1dFRDZERzY2hveA$cCJesPfv7EJYWAs0guhg7RU16bAH2502O3XigVRMv6c');

       $manager->persist($user);

       $admin = new User();
       $admin->setUsername('Admin');
       $admin->setPassword('$argon2id$v=19$m=65536,t=4,p=1$N2tjWmM5NGxIbHB2N090ZA$SVGbQoc9OM9Ro0n7XTgMdRavE9jPBFrba11ayiBExuU');
       $admin->setRoles(['ROLE_ADMIN']);

       $manager->persist($admin);

        $manager->flush();
    }
}
