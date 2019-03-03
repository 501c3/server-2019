<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/9/19
 * Time: 2:02 PM
 */

namespace App\Controller;


use App\DataTransformer\RegistrationToUserTransformer;
use App\Form\RegisterFormType;
use App\Repository\Access\UserRepository;
use App\Security\AccessAuthenticator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccessController extends BaseController
{
    /**
     * @Route("/access/login", name = "access_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils) : Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('access/login.html.twig',[
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    /**
     * @Route("/access/register", name = "access_register")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AccessAuthenticator $accessAuthenticator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public  function register(Request $request,
                              UserRepository $userRepository,
                              GuardAuthenticatorHandler $guardHandler,
                              AccessAuthenticator $accessAuthenticator) : Response
    {
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $registration = $form->getData();
//            try{
                $user = $userRepository->register($registration);
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $accessAuthenticator,
                    'main');
//            } catch (UniqueConstraintViolationException $e) {
//                $this->addFlash('error',"Username or email was previously used.  Please choose another.");
//            }
        }
        return $this->render('access/register.html.twig',[
            'registerForm'=>$form->createView()
        ]);
    }


    /**
     * @Route("/access/logout", name = "access_logout")
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('Authentication system failure.');
    }

    /**
     * @Route("/access/profile", name = "access_profile")
     */
    public function profile()
    {
        return $this->render('todo.html.twig',[
            'todo' => 'Build Profile Form'
        ]);
    }



}