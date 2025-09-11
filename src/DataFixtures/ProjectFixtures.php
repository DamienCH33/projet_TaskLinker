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
            ['TaskLinker', '2025-01-01', '2025-06-30'],
            ['Site vitrine Les Soeurs Marchand', '2025-02-15', '2025-09-30'],
        ];

        foreach ($projectsData as $index => [$title, $start, $end]) {
            $project = new Project();
            $project->setTitle($title);
            $project->setStartDate(new \DateTimeImmutable($start));
            $project->setEndDate(new \DateTimeImmutable($end));
            $project->setArchived(false);

            $manager->persist($project);


            $this->addReference('project_' . $index, $project);
        }

        $manager->flush();
    }
}
