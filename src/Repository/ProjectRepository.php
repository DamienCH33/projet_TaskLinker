<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * Récupère les projets accessibles par un utilisateur
     */
    public function findAccessibleProjects(Employee $employee): array
    {
        if (in_array('ROLE_ADMIN', $employee->getRoles())) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('p')
            ->join('p.employees', 'e')
            ->where('e = :employee')
            ->setParameter('employee', $employee)
            ->getQuery()
            ->getResult();
    }
}
