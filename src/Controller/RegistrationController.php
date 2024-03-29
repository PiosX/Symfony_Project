<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm()
            ;

            $form->handleRequest($request);

            if($form->isSubmitted())
            {
                $data = $form->getData(); 

                $user = new User();
                $user->setUsername($data['username']);
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword($user, $data['password'])
                );
                dump($user);
                $em = $this->getDoctrine()->getManager();

                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl(route:'app_login'));
            }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
