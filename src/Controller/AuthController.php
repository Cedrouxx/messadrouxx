<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

        }

        return $this->render('auth/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig', [

        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(){}
}
