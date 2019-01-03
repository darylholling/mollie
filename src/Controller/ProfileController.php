<?php
/**
 * Created by PhpStorm.
 * User: gebruiker
 * Date: 2-1-2019
 * Time: 14:48
 */

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Orderregel;
use App\Entity\Product;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\FactuurRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{

    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="profile_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('@FOSUser/Profile/show.html.twig');
    }

    /**
     * @Route("/{id}/update", name="profile_edit", methods="GET|POST")
     * @param Request $request
     * @param User $user
     * @return Response
     * @ParamConverter("User", class="App\Entity\User")
     */
    public function edit(Request $request, User $user): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_USER') && $user->getUsername() == $this->getUser()) {
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('profile_edit', ['id' => $user->getId()]);
            }
            return $this->render('profile/adresboek.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
//      TODO zorgen dat hij op eigen profiel komt
//      return $this->redirectToRoute('profile_edit', ['id' => $user->getId()]);
            return $this->render('message/error.html.twig');
        }
    }

    /**
     * @Route("/factuur", name="profile_factuur", methods={"GET"})
     * @param FactuurRepository $factuurRepository
     * @return Response
     */
    public function factuurindex(FactuurRepository $factuurRepository): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render('profile/factuur.html.twig', ['factuur' => $factuurRepository->findAll()]);
        } elseif ($this->isGranted("ROLE_USER")) {
            return $this->render('profile/factuur.html.twig', ['factuur' => $factuurRepository->findBy(['user' => $this->getUser()])]);
        } else {
            return $this->render('message/error.html.twig');
        }
    }


    /**
     * @Route("/factuur/{id}", name="profile_factuur_show", methods={"GET"})
     * @param Factuur $factuur
     * @return Response
     */
    public function factuurshow(Factuur $factuur): Response
    {
        $em = $this->getDoctrine()->getManager();
        $regels = $em->getRepository(Orderregel::class)->findBy(['factuur' => $factuur->getId()]);
        $products = $em->getRepository(Product::class)->findAll();
        if ($this->isGranted("ROLE_ADMIN") || $this->security->isGranted('ROLE_USER') && $factuur->getUser() == $this->getUser()) {
            return $this->render('profile/factuurshow.html.twig', [
                'factuur' => $factuur,
                'regels' => $regels,
                'products' => $products,
            ]);
        } else {
            return $this->render('message/error.html.twig');
        }
    }

}