<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
  /**
   * @param Request $request
   * @return Response
   */
  public function index(Request $request) {
    return $this->render("home.html.twig");
  }
}
