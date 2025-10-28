<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    #[Route('/welcome', name: 'app_welcome', methods: ['GET'])]
    public function welcom(): Response
    {
        return $this->render('log/welcome.html.twig');
    }

    #[Route('/registration', name: 'app_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $employee = new Employee();
        $employee->setStatus('CDI')
                ->setStartDate(new \DateTime());

        $form = $this->createForm(RegistrationType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $employee,
                $form->get('password')->getData()
            );
            $employee->setPassword($hashedPassword);

            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_welcom');
        }

        return $this->render('log/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response 
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('log/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
        
    }

    #[Route('/logout', name: 'app_logout')]
public function logout(): void
{
    throw new \LogicException('This method is intercepted by the logout key on your firewall.');
}

}
