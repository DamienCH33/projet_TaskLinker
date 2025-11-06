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
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

final class SecurityController extends AbstractController
{
    #[Route('/bienvenue', name: 'app_welcome', methods: ['GET'])]
    public function welcome(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('log/welcome.html.twig');
    }
    #[Route('/inscription', name: 'app_registration', methods: ['GET', 'POST'])]
    public function registration(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        /*GoogleAuthenticatorInterface $googleAuth*/
    ): Response {
        $employee = new Employee();

        $form = $this->createForm(RegistrationType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $employee,
                $form->get('password')->getData()
            );
            $employee->setPassword($hashedPassword);
            /*$employee->setGoogleAuthenticatorSecret($googleAuth->generateSecret());*/
            $employee->setStatus('CDI')
                ->setStartDate(new \DateTime());

            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('log/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/connexion', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('log/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method is intercepted by the logout key on your firewall.');
    }

    /*#[Route('/2fa/qrcode', name: '2fa_qrcode')]
    public function qrcode(GoogleAuthenticatorInterface $googleAuth): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Employee) {
            throw $this->createAccessDeniedException();
        }

        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($googleAuth->getQRContent($user))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(0)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build()
            ->getString();

        return new Response($qrCode, 200, ['Content-Type' => 'image/png']);
    }

    // Affichage de la page de login 2FA
    #[Route('/2fa', name: '2fa_login')]
    public function twoFactor(): Response
    {
        return $this->render('auth/2fa.html.twig', [
            'qrCode' => $this->generateUrl('2fa_qrcode')
        ]);
    }*/
}
