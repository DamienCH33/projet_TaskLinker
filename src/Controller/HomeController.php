<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]    
    #[IsGranted('ROLE_USER')]
    /**
     * index fonction affichant la homepage sur l'onglet project
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
}
