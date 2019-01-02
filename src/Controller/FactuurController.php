<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Orderregel;
use App\Entity\Product;
use App\Form\FactuurType;
use App\Repository\FactuurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/factuur")
 */
class FactuurController extends AbstractController
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route("/", name="factuur_index", methods={"GET"})
     * @param FactuurRepository $factuurRepository
     * @return Response
     */
    public function index(FactuurRepository $factuurRepository): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render('factuur/index.html.twig', ['factuur' => $factuurRepository->findAll()]);
        } elseif ($this->isGranted("ROLE_USER")) {
            return $this->render('factuur/index.html.twig', ['factuur' => $factuurRepository->findBy(['user' => $this->getUser()])]);
        } else {
            return new Response('not ok', 404);
        }
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
        $em = $this->getDoctrine()->getManager();
        $regels = $em->getRepository(Orderregel::class)->findBy(['factuur' => $factuur->getId()]);
        $products = $em->getRepository(Product::class)->findAll();
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render('factuur/show.html.twig', [
                'factuur' => $factuur,
                'regels' => $regels,
                'products' => $products,
            ]);
        } elseif ($this->security->isGranted('ROLE_USER') && $factuur->getUser() == $this->getUser()) {
            return $this->render('factuur/show.html.twig', [
                'factuur' => $factuur,
                'regels' => $regels,
                'products' => $products,
            ]);
        } else {
            return new Response('not ok', 404);
        }
    }

    /**
     * @Route("/{id}/edit", name="factuur_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Factuur $factuur
     * @return Response
     */
    public function edit(Request $request, Factuur $factuur): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_USER') && $factuur->getUser() == $this->getUser()) {
            $form = $this->createForm(FactuurType::class, $factuur);
        } else {
            return new Response('not ok', 404);

        }
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
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_USER') && $factuur->getUser() == $this->getUser()) {
            if ($this->isCsrfTokenValid('delete' . $factuur->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($factuur);
                $entityManager->flush();
            }
        } else {
            return new Response('not ok', 404);
        }

        return $this->redirectToRoute('factuur_index');
    }
}
