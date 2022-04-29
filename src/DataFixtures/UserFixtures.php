<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setUsername('Tristan');
        $user->setPassword('$2y$13$DoMh0rI7fiDAVQjcRI5DdO/qL0DvkROX1tMu.L/JEFCPYDZLhlLWq');

        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('$2y$13$0MeBdSFJ.cxrrYrCjwLEWOEDUkrODzK4fTTR6WGxmD4htSEe.J6YO');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
