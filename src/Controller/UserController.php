<?php
/**
 * Created by PhpStorm.
 * User: gebruiker
 * Date: 2-1-2019
 * Time: 10:50
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            return $this->render('user/index.html.twig', ['users' => $users]);
        } elseif ($this->security->isGranted('ROLE_USER')) {
            return $this->render('user/show.html.twig', ['user' => $this->getUser()]);
        } else{
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_USER') && $user == $this->getUser()) {
            return $this->render('user/show.html.twig', ['user' => $user]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }

    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_USER') && $user == $this->getUser()) {
            $form = $this->createForm(UserType::class, $user);
        } else {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Security("is_granted('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}