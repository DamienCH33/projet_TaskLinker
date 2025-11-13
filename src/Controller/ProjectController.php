<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_projet')]
    /**
     * list: fonction affichant la homepage sur l'onglet project
     *
     * @param  mixed $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $projects = $em->getRepository(Project::class)->findAccessibleProjects($user);

        return $this->render('index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: 'app_detail_project', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('PROJECT_VIEW', 'project')]
    /**
     * showDetailProject sert à montrer le contenue du projet selectionné
     *
     * @param  mixed $id
     * @param  mixed $em
     * @return Response
     */
    public function showDetailProject(int $id, EntityManagerInterface $em, Project $project): Response
    {
        $project = $em->getRepository(Project::class)->find($id);

        if (!$project) {
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute('app_projet');
        }

        return $this->render('project/projectDetails.html.twig', [
            'project' => $project,
            'users' => $project->getEmployees(),
        ]);
    }

    #[Route(path: '/ajouter', name: 'app_add_project', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    /**
     * addNewProject sert à ajouter/creer un nouveau projet
     *
     * @param  mixed $request
     * @param  mixed $em
     * @return Response
     */
    public function addNewProject(Request $request, EntityManagerInterface $em): Response
    {
        $newProject = new Project();
        $form = $this->createForm(ProjectType::class, $newProject);
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

        return $this->render('project/newProject.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/modifier', name: 'app_edit_project', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    /**
     * editProject sert à modifier un projet existant
     *
     * @param  mixed $request
     * @param  mixed $id
     * @param  mixed $em
     * @return Response
     */
    public function editProject(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $project = $em->getRepository(Project::class)->find($id);
        if (!$project) {
            throw $this->createNotFoundException("Ce projet n'existe pas");
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "La modification de votre projet a bien été prise en compte.");
            return $this->redirectToRoute('app_detail_project', ['id' => $project->getId()]);
        }

        return $this->render('project/editProject.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/{id}/archiver', name: 'app_archive_project', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    /**
     * archive fonction qui sert à archiver un projet
     *
     * @param  mixed $project
     * @param  mixed $em
     * @return Response
     */
    public function archive(Project $project, EntityManagerInterface $em): Response
    {
        $project->setArchived(true);
        $em->flush();

        $this->addFlash('success', 'Le projet a été archivé avec succès.');
        return $this->redirectToRoute('app_projet');
    }
}
