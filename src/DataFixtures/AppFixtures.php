<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Child;
use App\Entity\Slot;
use App\Entity\Reservation;
use DateTime;
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
        $admin->setEmail('laporte.aurelie91@gmail.com');
        $admin->setName('Aurélie ROGER');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, '12121984'));
        $manager->persist($admin);

        $admin = new User();
        $admin->setEmail('thomaroger@gmail.com');
        $admin->setName('Thomas ROGER');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, '07041987'));
        $manager->persist($admin);
/** 
        // Parent 1
        $p1 = new User();
        $p1->setEmail('parent1@example.com');
        $p1->setName('Parent One');
        $p1->setRoles(['ROLE_PARENT']);
        $p1->setPassword($this->hasher->hashPassword($p1, 'parentpass'));
        $manager->persist($p1);

        // Children
        $c1 = new Child();
        $c1->setFirstName('Hugo');
        $c1->setParent($p1);
        $manager->persist($c1);

        // Parent 2
        $p2 = new User();
        $p2->setEmail('parent2@example.com');
        $p2->setName('Parent Two');
        $p2->setRoles(['ROLE_PARENT']);
        $p2->setPassword($this->hasher->hashPassword($p2, 'parentpass'));
        $manager->persist($p2);

        $c2 = new Child();
        $c2->setFirstName('Lina');
        $c2->setParent($p2);
        $manager->persist($c2); 
*/
        // Slots
        $s = new Slot();
        $s->setLabel('Mercredi 17 septembre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '16/09/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '18/09/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 20 et 21 septembre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '19/09/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '22/09/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 24 septembre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '23/09/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '25/09/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 27 et 28 septembre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '26/09/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '29/09/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 1 octobre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '30/09/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '02/10/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 4 et 5 octobre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '03/10/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '06/10/2025 08:00'));
        $s->setType('Weekend');
        $s->setActive(true);
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 8 octobre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '07/10/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '09/10/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 11 et 12 octobre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '10/10/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '13/10/2025 08:00'));
        $s->setType('Weekend');
        $s->setActive(true);
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 15 octobre');
        $s->setStartAt( DateTime::createFromFormat('d/m/Y H:i', '14/10/2025 16:30'));
        $s->setEndAt( DateTime::createFromFormat('d/m/Y H:i', '16/10/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 5 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '04/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '06/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 8 et 9 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '7/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '10/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 12 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '10/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '13/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 15 et 16 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '14/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '17/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 19 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '18/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '20/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 22 et 23 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '21/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '24/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 26 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '25/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '27/11/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 29 et 30 novembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '28/11/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '1/12/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 3 décembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '2/12/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '4/12/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 6 et 7 décembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '5/12/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '8/12/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 10 décembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '9/12/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '11/12/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 13 et 14 décembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '12/12/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '15/12/2025 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 17 décembre');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '16/12/2025 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '18/12/2025 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 7 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '6/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '8/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 10 et 11 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '09/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '12/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 14 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '13/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '15/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 17 et 18 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '16/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '19/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 21 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '20/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '22/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 24 et 25 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '23/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '26/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 28 janvier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '27/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '29/01/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 31 janvier et 1 fevrier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '30/01/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '02/02/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 4 fevrier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '03/02/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '04/02/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 25 fevrier');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '24/02/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '26/02/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 28 fevrier et 1 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '27/02/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '02/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 4 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '03/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '05/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 7 et 8 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '06/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '09/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 11 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '10/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '12/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 14 et 15 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '13/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '16/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 18 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '17/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '19/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 21 et 22 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '20/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '23/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 25 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '17/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '19/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 28 et 29 mars');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '27/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '30/03/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 1 avril');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '31/03/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '02/04/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 22 avril');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '21/04/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '23/04/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 25 et 26 avril');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '24/04/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '27/04/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 29 avril');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '28/04/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '30/04/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 2 et 3 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '30/04/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '04/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 6 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '05/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '07/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 9 et 10 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '07/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '11/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 13 mai et Weekend du 16 et 17 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '12/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '18/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 20 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '19/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '21/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 23 et 24 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '22/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '26/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 27 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '26/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '28/05/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 30 et 31 mai');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '29/05/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '01/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 3 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '02/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '04/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 6 et 7 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '05/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '08/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 10 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '09/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '11/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 13 et 14 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '12/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '15/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 17 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '16/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '18/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 20 et 21 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '19/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '22/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);


        $s = new Slot();
        $s->setLabel('Mercredi 24 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '23/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '25/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Weekend du 27 et 28 juin');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '26/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '29/06/2026 08:00'));
        $s->setActive(true);
        $s->setType('Weekend');
        $manager->persist($s);

        $s = new Slot();
        $s->setLabel('Mercredi 1 juillet');
        $s->setStartAt(DateTime::createFromFormat('d/m/Y H:i', '30/06/2026 16:30'));
        $s->setEndAt(DateTime::createFromFormat('d/m/Y H:i', '02/07/2026 08:00'));
        $s->setActive(true);
        $s->setType('Mercredi');
        $manager->persist($s);

        $manager->flush();
    }
}
