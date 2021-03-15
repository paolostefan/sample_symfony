<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }
}
