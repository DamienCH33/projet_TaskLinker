<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\AddTaskType;
use App\Form\TaskEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

final class TasksController extends AbstractController
{
    #[Route(path: '/project/{id}/task/add', name: 'app_add_task', methods: ['GET', 'POST'])]
    public function addNewTask(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        $newTask = new Task();
        $newTask->setProject($project);

        $form = $this->createForm(AddTaskType::class, $newTask);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($newTask->getDeadline() === null) {
                $newTask->setDeadline(new \DateTime());
            }

            $em->persist($newTask);
            $em->flush();

            $this->addFlash('success', "Tâche ajoutée avec succès.");
            return $this->redirectToRoute('app_detail_project', ['id' => $newTask->getProject()->getId()]);
        }

        return $this->render('newTask.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }


    #[Route(path: '/project/{projectId}/task/{taskId}/edit', name: 'app_edit_task', requirements: ['projectId' => '\d+', 'taskId' => '\d+'],
    methods: ['GET', 'POST'])]
    public function editTask(Request $request,#[MapEntity(id: 'projectId')] Project $project,#[MapEntity(id: 'taskId')] Task $task,EntityManagerInterface $em): Response
    {
        if (!$task) {
            throw $this->createNotFoundException("Cette tâche n'existe pas");
        }

        $form = $this->createForm(TaskEditType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "La modification de votre tâche a bien été prise en compte.");
            return $this->redirectToRoute('app_detail_project', ['id' => $project->getId()]);
        }

        return $this->render('editTask.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'task' => $task,
        ]);
    }
    #[Route(
    path: '/project/{projectId}/task/{taskId}/delete',name: 'app_delete_task', requirements: ['projectId' => '\d+', 'taskId' => '\d+'],
    methods: ['POST'])]
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
