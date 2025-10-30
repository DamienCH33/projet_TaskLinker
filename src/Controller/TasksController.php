<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

final class TasksController extends AbstractController
{
    #[Route(path: '/project/{id}/task/add', name: 'app_add_task', methods: ['GET', 'POST'])]    
    /**
     * addNewTask permet d'ajouter/créer une nouvelle tâche au projet
     *
     * @param  mixed $request
     * @param  mixed $id
     * @param  mixed $em
     * @return Response
     */
    public function addNewTask(Request $request, int $id, EntityManagerInterface $em): Response
{
    $project = $em->getRepository(Project::class)->find($id);
    if (!$project) {
        throw $this->createNotFoundException("Ce projet n'existe pas.");
    }

    $newTask = new Task();
    $newTask->setProject($project);

    $form = $this->createForm(TaskType::class, $newTask);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if (!$newTask->getDeadline()) {
            $newTask->setDeadline(new \DateTimeImmutable());
        }
        $em->persist($newTask);
        $em->flush();

        $this->addFlash('success', "Tâche ajoutée avec succès.");
        return $this->redirectToRoute('app_detail_project', ['id' => $project->getId()]);
    }

    return $this->render('task/newTask.html.twig', [
        'form' => $form->createView(),
        'project' => $project,
    ]);
}

    #[Route(path: '/project/{projectId}/task/{taskId}/edit', name: 'app_edit_task', requirements: ['projectId' => '\d+', 'taskId' => '\d+'],
    methods: ['GET', 'POST'])]    
    /**
     * editTask Permet de modifier les tâches d'un projet
     *
     * @param  mixed $request
     * @param  mixed $project
     * @param  mixed $task
     * @param  mixed $em
     * @return Response
     */
    public function editTask(Request $request,#[MapEntity(id: 'projectId')] Project $project,#[MapEntity(id: 'taskId')] Task $task,EntityManagerInterface $em): Response
    {
        if (!$task) {
            throw $this->createNotFoundException("Cette tâche n'existe pas");
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "La modification de votre tâche a bien été prise en compte.");
            return $this->redirectToRoute('app_detail_project', ['id' => $project->getId()]);
        }

        return $this->render('task/editTask.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'task' => $task,
        ]);
    }
    #[Route(
    path: '/project/{projectId}/task/{taskId}/delete',name: 'app_delete_task', requirements: ['projectId' => '\d+', 'taskId' => '\d+'],
    methods: ['POST'])]    
    /**
     * deleteTask Permet de supprimer une tâche d'un projet
     *
     * @return void
     */
    public function deleteTask(
        #[MapEntity(id: 'projectId')] Project $project,
        #[MapEntity(id: 'taskId')] Task $task,
        EntityManagerInterface $em
    ): Response
    {
        if (!$task) {
            $this->addFlash('danger', "Cette tâche n'existe pas.");
            return $this->redirectToRoute('app_detail_project', ['id' => $project->getId()]);
        }

        $em->remove($task);
        $em->flush();

        $this->addFlash('success', "La tâche a bien été supprimée.");
        return $this->redirectToRoute('app_detail_project', ['id' => $project->getId()]);
    }
}
