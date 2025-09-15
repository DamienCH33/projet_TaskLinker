<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {

        $team = $em->getRepository(Employee::class)->findAll();

        return $this->render('team.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/team/{id}/delete', name: 'app_delete_team',  requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteTeamMember(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $team = $em->getRepository(Employee::class)->find($id);
        if (!$team) {
            $this->addFlash('danger', "Cet employé n'existe pas.");
            return $this->redirectToRoute('app_team');
        }
        if (count($team->getTasks()) > 0) {
            $this->addFlash('danger', "Impossible de supprimer cet employé car il a encore des tâches assignées.");
            return $this->redirectToRoute('app_team');
        }
        $em->remove($team);
        $em->flush();

        $this->addFlash('success', "Cet employé a bien été supprimé.");
        return $this->redirectToRoute('app_team');
    }
}
