<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\EmployeeFixtures;
use App\DataFixtures\ProjectFixtures;


class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tasksData = [
            [
                'title' => "Gestion des droits d'accès",
                'description' => "Un employé ne peut accéder qu'à ses projets.",
                'deadline' => new \DateTime('+15 days'),
                'status' => 'To Do',
                'employee' => 0,
                'project' => 0,
            ],
            [
                'title' => "Développement de la page employé",
                'description' => 'Page employé avec liste des employés',
                'deadline' => new \DateTime('+30 days'),
                'status' => 'Doing',
                'employee' => 1,
                'project' => 0,
            ],
            [
                'title' => "Développement de la structure globale",
                'description' => 'Intégrer les maquettes',
                'deadline' => new \DateTime('+30 days'),
                'status' => 'Doing',
                'employee' => 1,
                'project' => 0,
            ],
            [
                'title' => "Développement de la page projet",
                'description' => 'Page projet avec listes des tâches, édition.',
                'deadline' => new \DateTime('+30 days'),
                'status' => 'Done',
                'employee' => 2,
                'project' => 0,
            ],
        ];

        foreach ($tasksData as $index => $data) {
            $task = new Task();
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setDeadline($data['deadline']);
            $task->setStatus($data['status']);

            $employee = $this->getReference('employee_' . $data['employee'], \App\Entity\Employee::class);
            $project  = $this->getReference('project_' . $data['project'], \App\Entity\Project::class);

            $task->addEmployee($employee);
            $task->setProject($project);
            $project->addTask($task);

            $manager->persist($task);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EmployeeFixtures::class,
            ProjectFixtures::class,
        ];
    }
}
