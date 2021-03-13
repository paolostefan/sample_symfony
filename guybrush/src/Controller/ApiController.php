<?php

namespace App\Controller;

use App\Entity\Poi;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function poiSearch(Request $request)
    {
        $results = $this->getDoctrine()->getRepository(Poi::class)->search($request->get('q'));

        return new JsonResponse(['results' => $results]);
    }
}
