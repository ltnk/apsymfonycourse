<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController

{

    /**
     * @Route('/', name='index')
     */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route('/products', name='products')
     */
    function showProducts(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        return $this->render('test.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/category", name="category")
     */
    public function category(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category.html.twig', ['categories' => $categories]);
    }
}
