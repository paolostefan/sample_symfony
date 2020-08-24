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
        return $this->render('super/index.html.twig', [
            'controller_name' => 'SuperController',
        ]);
    }
}
