<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Orderregel;
use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="cart_index")
     */
    public function index()
    {
        // get the cart from  the session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart', array());

        // fetch the information using query and ids in the cart
        if ($cart != '') {
            if (isset($cart)) {
                $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
            } else {
                return $this->render('Cart/index.html.twig', array(
                    'empty' => true,
                ));
            }
            return $this->render('Cart/index.html.twig', array(
                'empty' => false,
                'product' => $product,
            ));
        } else {
            return $this->render('Cart/index.html.twig', array(
                'empty' => true,
            ));
        }
    }

    /**
     * @Route("/add/{id}", name="cart_add")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        // get the cart from  the session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart', array());

        //TODO zorgen dat aantal een invul veld wordt

        if ($product == NULL) {
            $this->get('session')->setFlash('notice', 'This product is not available');
            return $this->redirect($this->generateUrl('cart_index'));
        } else {
            if (isset ($cart[$id])) {
                $cart[$id]++;
            } else {
                $cart[$id] = 1;
            }
            $session->set('cart', $cart);
            return $this->redirect($this->generateUrl('cart_index'));
        }
    }

    /**
     * @Route("/checkout", name="checkout")
     * @throws \Exception
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function checkoutAction()
    {
        // verwerken van regels in de nieuwe factuur voor de huidige klant.
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart', array());
        // aanmaken factuur regel.
        $em = $this->getDoctrine()->getManager();
        $factuur = new Factuur();
        $factuur->setTimestamp(new \DateTime("now"));
        $factuur->setUser($this->getUser());
        if (isset($cart)) {
            $em->persist($factuur);
            $em->flush();
            // put basket in database
            foreach ($cart as $id => $quantity) {
                $regel = new Orderregel();
                $regel->setFactuur($factuur);
                $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
                $regel->setAantal($quantity);
                $regel->setProduct($product);
                $em->persist($regel);
                $em->flush();
            }
        }
        $session->clear();
        return $this->redirectToRoute('molly_new');
    }

    /**
     * @Route("/remove/{id}", name="cart_remove")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        // check the cart
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $cart = $session->get('cart', array());
        // if it doesn't exist redirect to cart index page.
        if (!$cart[$id]) {
            $this->redirect($this->generateUrl('cart_index'));
        }


        // check if the $id already exists in it.
        if (isset($cart[$id])) {
//      zorgt ervoor dat remove 1 record verwijderd
//            $cart[$id] = $cart[$id] - 1;
//            if ($cart[$id] < 1) {
//      zorgt ervoor dat remove het gehele item [$id] uit de wagen gooit
                unset($cart[$id]);
//            }
        } else {
            return $this->redirect($this->generateUrl('cart_index'));
        }
        $session->set('cart', $cart);
        return $this->redirect($this->generateUrl('cart_index'));
    }

}