<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $projectsData = [
            ['TaskLinker', '2025-01-01', '2025-06-30', [0,1]],
            ['Site vitrine Les Soeurs Marchand', '2025-02-15', '2025-09-30', [1,2]],
        ];

        foreach ($projectsData as $index => [$title, $start, $end, $employeeIndices]) {
            $project = new Project();
            $project->setTitle($title);
            $project->setStartDate(new \DateTimeImmutable($start));
            $project->setEndDate(new \DateTimeImmutable($end));
            $project->setArchived(false);

            foreach ($employeeIndices as $empIndex) {
                $employee = $this->getReference('employee_' . $empIndex, \App\Entity\Employee::class);
                $project->addEmployee($employee);
            }

            $manager->persist($project);


            $this->addReference('project_' . $index, $project);
        }

        $manager->flush();
    }
}
