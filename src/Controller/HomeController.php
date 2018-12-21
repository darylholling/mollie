<?php
/**
 * Created by PhpStorm.
 * User: DarylHolling
 * Date: 21-12-2018
 * Time: 12:17
 */

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="product_overview")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/overview.html.twig', ['products' => $productRepository->findAll()]);

    }

}