<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $employeesData = [
            [
                'firstName' => 'Alice',
                'lastName' => 'Durand',
                'email' => 'alice.durand@driblet.com',
                'startDate' => new \DateTime('2022-01-15'),
                'status' => 'CDI'
            ],
            [
                'firstName' => 'Richard',
                'lastName' => 'Martin',
                'email' => 'richard.martin@driblet.com',
                'startDate' => new \DateTime('2023-05-01'),
                'status' => 'CDD'
            ],
            [
                'firstName' => 'Clara',
                'lastName' => 'Legrand',
                'email' => 'clara.legrand@driblet.com',
                'startDate' => new \DateTime('2024-03-20'),
                'status' => 'Freelance'
            ],
        ];

        foreach ($employeesData as $index => $data) {
            $employee = new Employee();
            $employee->setFirstName($data['firstName']);
            $employee->setLastName($data['lastName']);
            $employee->setEmail($data['email']);
            $employee->setStartDate($data['startDate']);
            $employee->setStatus($data['status']);

            $hashedPassword = $this->passwordHasher->hashPassword($employee, 'MotDePasse123*');
            $employee->setPassword($hashedPassword);

            $manager->persist($employee);

            $this->addReference('employee_' . $index, $employee);
        }
        $manager->flush();
    }
}
