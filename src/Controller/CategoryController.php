<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function ShowCategory(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/category/add", name="addCategory")
     */
    public function addCategory()
    {
        // Ajouter une catégorie manuellement 
        /*         $em = $this->getDoctrine()->getManager();
        $category = new Category;
        $category->setName('Xiaomi')->setDescription('Made in China')->setSlug('xiaomi');

        $em->persist($category);

        $em->flush();
        */
        return $this->render('category/add-category.html.twig', []); 
    }
}
