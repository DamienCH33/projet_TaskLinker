<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\TeamEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team', methods: ['GET'])]    
    /**
     * index Permet l'affichage de l'onglet équipe 
     *
     * @param  mixed $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {

        $team = $em->getRepository(Employee::class)->findAll();

        return $this->render('team.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/team/{id}/delete', name: 'app_delete_team',  requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]    
    /**
     * deleteTeamMember Permet la suppression d'un membre de l'équipe
     *
     * @param  mixed $request
     * @param  mixed $id
     * @param  mixed $em
     * @return Response
     */
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

    #[Route(path: '/team/edit/{id}', name: 'app_edit_team', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]    
    /**
     * editTeamMember Permet la modification du profil du membre
     *
     * @param  mixed $request
     * @param  mixed $id
     * @param  mixed $em
     * @return Response
     */
    public function editTeamMember(Request $request, int $id, EntityManagerInterface $em): Response
    {
        $team = $em->getRepository(Employee::class)->find($id);
        if (!$team) {
            throw $this->createNotFoundException("Cet employé n'existe pas");
        }

        $form = $this->createForm(TeamEditType::class, $team);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "Votre modification a bien été prise en compte.");
            return $this->redirectToRoute('app_team');
        }
        return $this->render('editTeam.html.twig', [
            'form' => $form->createView(),
            'employee' => $team,
        ]);
    }
}
