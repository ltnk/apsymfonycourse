<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController

{
    /**
     * @Route("", name="index")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        return $this->render('index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/detailed-product/{id}-{slug}", name="detailedProducts")
     */
    function detailedProducts(Product $product)
    {
        return $this->render('product/detailed-product.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/success", name="success")
     */
    public function success()
    {
        return $this->render('success.html.twig');
    }

    /**
     * @Route("/error", name="error")
     */
    public function error()
    {
        return $this->render('error.html.twig');
    }
}
