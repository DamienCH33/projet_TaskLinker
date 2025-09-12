<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class ProjectController extends AbstractController
{
    #[Route('/project/{id}',name:'app_detail_project', requirements: ['id' => '\d+'], methods:['GET'])]
    public function showDetailProject(int $id, EntityManagerInterface $em): Response
    {
        $project = $em->getRepository(Project::class)->find($id);

        if(!$project){
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute('app_home');
        }

        $users = $project->getEmployees();

        return $this->render('projectDetails.html.twig',[
            'project'=> $project,
            'users' => $users,
        ]);
    }
}
