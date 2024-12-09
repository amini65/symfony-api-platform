<?php

namespace App\DataFixtures;

use App\Constant\UserRoles;
use App\Entity\Company;
use App\Entity\User;
use App\Factory\CompanyFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Zenstruck\Foundry\lazy;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $encoder,private EntityManagerInterface $entityManager)
    {
        $this->hasher = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        CompanyFactory::createMany(100);
        UserFactory::createMany(5);

        $company = $this->entityManager->getRepository(Company::class)->findAll();

        $user = new User();
        $user->setEmail('user@example.com')
            ->setRole(UserRoles::ROLE_SUPER_ADMIN)
            ->setCompany($company[0])
            ->setName('Admin')
            ->setPassword($this->hasher->hashPassword($user, '12345678'));
        $manager->persist($user);
        $manager->flush();
    }
}
