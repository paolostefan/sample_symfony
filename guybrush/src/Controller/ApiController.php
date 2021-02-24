<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Repository\PoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("poi/search", name="api_poi_search")
     * @param Request $request
     * @return JsonResponse
     */
    public function poiSearch(Request $request)
    {
        $results = $this->getDoctrine()->getRepository(Poi::class)->search($request->get('q'));

        return new JsonResponse(['results' => $results]);
    }

    /**
     * @Route("poi/page", name="api_poi_page")
     * @param Request $request
     * @return JsonResponse
     */
    public function poiDataTablesPage(Request $request): JsonResponse
    {
        /** @var PoiRepository $repo */
        $repo = $this->getDoctrine()->getRepository('App:Poi');
        list($data, $filtered, $total) = $repo->getDataTablesPage($request);
        $json = [
          "draw"            => $request->get("draw", 1),
          "recordsTotal"    => $total,
          "recordsFiltered" => $filtered,
          "data"            => $data,
        ];

        return new JsonResponse($json);
    }
}
