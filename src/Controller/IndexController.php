<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController

{
    /**
     * @Route("", name="index")
     */
    public function index()
    {
        return $this->render('base.html.twig');
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

    /**
     * @Route("/category/list", name="categoriesList")
     */
    public function categoryList()
    {
        return $this->render('category/index.html.twig');
    }

    /**
     * @Route("/products/list", name="productsList")
     */
    public function productsList()
    {
        return $this->render('productsList.html.twig');
    }
}
