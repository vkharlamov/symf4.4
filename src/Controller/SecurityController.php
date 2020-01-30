<?php

namespace App\Controller;

use App\DTO\UserRegistration;
use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        SecurityService $securityService,
        UserRegistration $userRegister
    ): Response {
        $form = $this->createForm(UserRegistrationType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid() && $form[UserRegistrationType::AGREE_TERMS]->getData()) {
            $userRegister->set(
                $form[UserRegistrationType::EMAIL]->getData(),
                $form[UserRegistrationType::FULL_NAME]->getData(),
                $form[UserRegistrationType::PLAIN_PASSWORD]->getData()
            );
            /** Create user and send confirmation email */
            $securityService->register($userRegister);

            $this->addFlash('success', 'Congrats. To complete registration check your email');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
