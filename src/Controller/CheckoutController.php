<?php
/**
 * Created by PhpStorm.
 * User: DarylHolling
 * Date: 4-1-2019
 * Time: 13:00
 */

namespace App\Controller;

use App\Entity\Betaling;
use App\Entity\Factuur;
use App\Entity\Orderregel;
use App\Entity\Product;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;
use Mollie\Api\MollieApiClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
    /**
     * @Route("/mollie", name="mollie_new", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws ApiException
     * @throws IncompatiblePlatform
     */
    public function mollie()
    {
        $mollie = new MollieApiClient();
        $mollie->setApiKey("test_TqNqMzGfxk9eqjxEVFgDEEcbKyrDW4");

        $orderId = time();

        /*
         * Determine the url parts to these example files.
         */
        $redirectUrl = $this->generateUrl('mollie_checkout', [
            'orderId' => $orderId,
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        $webhookUrl = $this->generateUrl('hook_show', [

        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => "10.00"
            ],
            "description" => "My first payment" . $orderId,
            "redirectUrl" => $redirectUrl,
//            "webhookUrl" => $webhookUrl,
            "webhookUrl" => "https://d17b6858.ngrok.io/mollie/webhook",
            "metadata" => [
                "order_id" => $orderId,
            ],
        ]);
        if ($payment) {
            $betaling = new Betaling();
            $betaling->setOrderId($payment->metadata->order_id);
            $betaling->setDescription($payment->description);
            $betaling->setStatus($payment->status);
            $betaling->setHook($payment->id);

            $em = $this->getDoctrine()->getManager();
            $em->persist($betaling);
            $em->flush();
        }

//        Bij annuleren payment
        if ($payment->isOpen() === FALSE) {
            return $this->redirect('mollie/canceled.html.twig');
        }
        return $this->redirect($payment->getCheckoutUrl());

    }


    /**
     * @Route("/checkout", name="mollie_checkout")
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
        return $this->redirectToRoute('order_complete', ['factuur' => $factuur]);
    }

    /**
     * @Route("/complete", name="order_complete")
     */
    public function complete()
    {
        return $this->render('mollie/complete.html.twig');
    }


}