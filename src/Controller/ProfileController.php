<?php
/**
 * Created by PhpStorm.
 * User: gebruiker
 * Date: 2-1-2019
 * Time: 14:48
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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
     * @Route("/{id}/edit", name="profile_index", methods="GET|POST")
     * @param Request $request
     * @param User $user
     * @return Response
     * @ParamConverter("User", class="App\Entity\User")
     */
    public function index(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
//        if ($this->security->isGranted('ROLE_USER' && $user == $this->getUser())) {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }
        return $this->render('@FOSUser/Profile/show.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
//    }

}