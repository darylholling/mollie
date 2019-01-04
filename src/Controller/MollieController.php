<?php

namespace App\Controller;

use App\Entity\Betaling;
use App\Repository\BetalingRepository;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;
use Mollie\Api\MollieApiClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mollie")
 */
class MollieController extends AbstractController
{
    /**
     * @Route("/", name="mollie_index")
     * @param BetalingRepository $betalingRepository
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(BetalingRepository $betalingRepository)
    {
        return $this->render('mollie/index.html.twig', [
            'betalings' => $betalingRepository->findAll()
        ]);
    }

    /**
     * @Route("/webhook", name="hook_show", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws ApiException
     * @throws IncompatiblePlatform
     */
    public function hook(Request $request): Response
    {
        $mollie = new MollieApiClient();
        $mollie->setApiKey("test_TqNqMzGfxk9eqjxEVFgDEEcbKyrDW4");
        $payment = $mollie->payments->get($request->request->get('id'));
        if (!$payment) {
            throw new BadRequestHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        $betaling = $this->getDoctrine()
            ->getRepository(Betaling::class)
            ->findOneBy(['hook' => $payment->id]);

        $betaling->setStatus($payment->status);
        $em->flush();

        return $this->render('message/error.html.twig');
    }

    /**
     * @Route("/payments/{orderId}", name="payments_show", methods={"GET"})
     * @param Betaling $betaling
     * @return Response
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function show(Betaling $betaling): Response
    {
        switch ($betaling->getStatus()) {
            case 'paid':
                return $this->render('mollie/paid.html.twig',
                    [
                        'betaling' => $betaling,
                    ]
                );
                break;
            case 'canceled':
                return $this->render('mollie/canceled.html.twig',
                    [
                        'betaling' => $betaling,
                    ]
                );
                break;
            case 'failed':
                return $this->render('mollie/canceled.html.twig',
                    [
                        'betaling' => $betaling,
                    ]
                );
                break;
            default:
                return $this->render('message/error.html.twig');
        }
    }
}