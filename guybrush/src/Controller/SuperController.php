<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SuperController extends AbstractController
{
    /**
     * @Route("/super", name="super")
     */
    public function index()
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
    public function mapEmpty()
    {
        return $this->render('map_empty.html.twig');
    }

    /**
     * @Route("/map", name="map_search")
     */
    public function mapAction()
    {
        return $this->render('map_search.html.twig');
    }
}
