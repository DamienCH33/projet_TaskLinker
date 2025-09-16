<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\AddProjectType;
use App\Form\ProjectEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


final class ProjectController extends AbstractController
{
    #[Route('/project/{id}', name: 'app_detail_project', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showDetailProject(int $id, EntityManagerInterface $em): Response
    {
        $project = $em->getRepository(Project::class)->find($id);

        if (!$project) {
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute('app_home');
        }

        $users = $project->getEmployees();

        return $this->render('projectDetails.html.twig', [
            'project' => $project,
            'users' => $users,
        ]);
    }
    #[Route(path: '/project/add', name: 'app_add_project', methods: ['GET', 'POST'])]
    public function addNewProject(Request $request, EntityManagerInterface $em): Response
    {
        $newProject = new Project();
        $form = $this->createForm(AddProjectType::class, $newProject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($newProject->getStartDate() === null) {
                $newProject->setStartDate(new \DateTimeImmutable());
            }

            $em->persist($newProject);
            $em->flush();

            $this->addFlash('success', "Projet créé avec succès.");
            return $this->redirectToRoute('app_detail_project', ['id' => $newProject->getId()]);
        }

        return $this->render('newProject.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route(path: '/project/edit/{id}', name: 'app_edit_project', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editProject(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $project = $em->getRepository(Project::class)->find($id);
        if (!$project) {
            throw $this->createNotFoundException("Ce projet n'existe pas");
        }

        $form = $this->createForm(ProjectEditType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "La modification de votre projet a bien été prise en compte.");
            return $this->redirectToRoute('app_home');
        }
        return $this->render('editProject.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}
