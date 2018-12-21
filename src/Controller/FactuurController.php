<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Form\FactuurType;
use App\Repository\FactuurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factuur")
 */
class FactuurController extends AbstractController
{
    /**
     * @Route("/", name="factuur_index", methods={"GET"})
     * @param FactuurRepository $factuurRepository
     * @return Response
     */
    public function index(FactuurRepository $factuurRepository): Response
    {
        return $this->render('factuur/index.html.twig', ['factuurs' => $factuurRepository->findAll()]);
    }

    /**
     * @Route("/new", name="factuur_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $factuur = new Factuur();
        $form = $this->createForm(FactuurType::class, $factuur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($factuur);
            $entityManager->flush();

            return $this->redirectToRoute('factuur_index');
        }

        return $this->render('factuur/new.html.twig', [
            'factuur' => $factuur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factuur_show", methods={"GET"})
     * @param Factuur $factuur
     * @return Response
     */
    public function show(Factuur $factuur): Response
    {
        return $this->render('factuur/show.html.twig', ['factuur' => $factuur]);
    }

    /**
     * @Route("/{id}/edit", name="factuur_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Factuur $factuur
     * @return Response
     */
    public function edit(Request $request, Factuur $factuur): Response
    {
        $form = $this->createForm(FactuurType::class, $factuur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factuur_index', ['id' => $factuur->getId()]);
        }

        return $this->render('factuur/edit.html.twig', [
            'factuur' => $factuur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factuur_delete", methods={"DELETE"})
     * @param Request $request
     * @param Factuur $factuur
     * @return Response
     */
    public function delete(Request $request, Factuur $factuur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factuur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($factuur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('factuur_index');
    }
}
