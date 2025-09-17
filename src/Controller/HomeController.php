<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]    
    /**
     * index fonction affichant la homepage sur l'onglet project
     *
     * @param  mixed $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(Project::class)->findAll();

        return $this->render('index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
