<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Child;
use App\Entity\Slot;
use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setName('Enseignante');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'adminpass'));
        $manager->persist($admin);

        // Parent 1
        $p1 = new User();
        $p1->setEmail('parent1@example.com');
        $p1->setName('Parent One');
        $p1->setRoles(['ROLE_PARENT']);
        $p1->setPassword($this->hasher->hashPassword($p1, 'parentpass'));
        $manager->persist($p1);

        // Parent 2
        $p2 = new User();
        $p2->setEmail('parent2@example.com');
        $p2->setName('Parent Two');
        $p2->setRoles(['ROLE_PARENT']);
        $p2->setPassword($this->hasher->hashPassword($p2, 'parentpass'));
        $manager->persist($p2);

        // Children
        $c1 = new Child();
        $c1->setFirstName('Hugo');
        $c1->setParent($p1);
        $manager->persist($c1);

        $c2 = new Child();
        $c2->setFirstName('Lina');
        $c2->setParent($p2);
        $manager->persist($c2);

        // Slots
        $s1 = new Slot();
        $s1->setLabel('Mardi soir → Jeudi matin');
        $s1->setStartAt(new \DateTime('next tuesday 19:00'));
        $s1->setEndAt((new \DateTime('next thursday 08:00')));
        $s1->setActive(true);
        $manager->persist($s1);

        $s2 = new Slot();
        $s2->setLabel('Vendredi soir → Lundi matin');
        $s2->setStartAt(new \DateTime('next friday 19:00'));
        $s2->setEndAt((new \DateTime('next monday 08:00')));
        $s2->setActive(true);
        $manager->persist($s2);

        $s3 = new Slot();
        $s3->setLabel('Vendredi soir → Lundi matin');
        $s3->setStartAt(new \DateTime('next friday 19:00'));
        $s3->setEndAt((new \DateTime('next monday 08:00')));
        $s3->setActive(true);
        $manager->persist($s3);

        $s4 = new Slot();
        $s4->setLabel('Vendredi soir → Lundi matin');
        $s4->setStartAt(new \DateTime('next friday 19:00'));
        $s4->setEndAt((new \DateTime('next monday 08:00')));
        $s4->setActive(true);
        $manager->persist($s4);

        $manager->flush();
    }
}
