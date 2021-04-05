<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SuperController extends AbstractController
{
    /**
     * @Route("/super", name="super")
     */
    public function index(): Response
    {
        return $this->render(
          'super/index.html.twig',
          [
            'controller_name' => 'SuperController',
          ]
        );
    }

    /**
     * @Route("/map/empty", name="map_empty")
     */
    public function mapEmpty(): Response
    {
        return $this->render('map_empty.html.twig');
    }

    /**
     * @Route("/map", name="map_search")
     */
    public function mapAction(): Response
    {
        return $this->render('map_search.html.twig');
    }

    /**
     * @Route("/login", name="login")
     * @param Session $session
     * @return Response
     */
    public function login(Session $session): Response
    {
        return $this->render('login.html.twig', ['messages' => $session->getFlashBag()->all()]);
    }

    /**
     * @Route("/signup", name="signup")
     * @param Request $request
     * @param UserPasswordEncoderInterface $pwEncoder
     * @param Session $session
     * @return Response
     */
    public function signup(Request $request, UserPasswordEncoderInterface $pwEncoder, Session $session): Response
    {
        if ($request->getMethod() === 'POST') {
            $pw = $request->get('password');
            if (!$pw || $pw !== $request->get('confirm')) {
                $session->getFlashBag()->add(
                  'danger',
                  'Please enter the same password twice.'
                );

                return $this->redirectToRoute('signup');
            }

            $user = new User();
            $user
              ->setEmail($request->get('email'))
              ->setFullName($request->get('full_name'))
              ->setPassword($pwEncoder->encodePassword($user, $request->get('password')));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            try {
                $em->flush();
                $session->getFlashBag()->add(
                  'success',
                  'User created successfully. You can now log in with username '.
                  $user->getEmail().' and your chosen password.'
                );

                return $this->redirectToRoute('login');
            } catch (UniqueConstraintViolationException) {
                $session->getFlashBag()->add(
                  'danger',
                  'Email '.$user->getEmail().' already registered. Please use a different one.'
                );

                return $this->redirectToRoute('signup');
            } catch(\Exception $exception){
                $session->getFlashBag()->add(
                  'danger',
                  'Cannot save: '.$exception->getMessage()
                );

                return $this->redirectToRoute('signup');
            }
        }

        $messages = $session->getFlashBag()->all();

        return $this->render('signup.html.twig', ['messages' => $messages]);
    }
}
