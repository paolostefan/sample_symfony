<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Form\PoiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/poi")
 */
class PoiController extends AbstractController
{
    /**
     * @Route("/", name="poi_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('poi/index.html.twig');
    }

    /**
     * @Route("/new", name="poi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $poi = new Poi();
        $form = $this->createForm(PoiType::class, $poi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poi);
            $entityManager->flush();

            return $this->redirectToRoute('poi_index');
        }

        return $this->render(
          'poi/new.html.twig',
          [
            'poi'  => $poi,
            'form' => $form->createView(),
          ]
        );
    }

    /**
     * @Route("/{id}", name="poi_show", methods={"GET"})
     */
    public function show(Poi $poi): Response
    {
        return $this->render(
          'poi/show.html.twig',
          [
            'poi' => $poi,
          ]
        );
    }

    /**
     * @Route("/{id}/edit", name="poi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Poi $poi): Response
    {
        $form = $this->createForm(PoiType::class, $poi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poi_index');
        }

        return $this->render(
          'poi/edit.html.twig',
          [
            'poi'  => $poi,
            'form' => $form->createView(),
          ]
        );
    }

    /**
     * @Route("/{id}", name="poi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Poi $poi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($poi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('poi_index');
    }
}
