<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team', methods:['GET'])]
    public function index(EntityManagerInterface $em): Response
    {

        $team = $em->getRepository(Employee::class)->findAll();
       
        return $this->render('team.html.twig', [
            'team' => $team,
        ]);
    }
}
