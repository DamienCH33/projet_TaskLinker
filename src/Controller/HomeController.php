<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $ProjectRepository): Response
    {
        $projects = $ProjectRepository->findAll();

        return $this->render('index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
