<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/poi/search", name="api_poi_search")
     * @return JsonResponse
     */
    public function poiSearch(Request $request)
    {
        return new JsonResponse(['you_searched'=>$request->get('q')]);
    }
}
