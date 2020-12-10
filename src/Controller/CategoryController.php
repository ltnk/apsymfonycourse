<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
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
    public function addCategory(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em): Response
    {

        $category = new Category;
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($category);
            $em->flush();

            // Go directly on the page category after removing the category
            $categories = $categoryRepository->findAll();
            return $this->render('category/index.html.twig', ['categories' => $categories]);
        }

        return $this->render('category/add-category.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/detailed-category/{id}", name="detailedCategory")
     */
    function detailedCategory(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, $id)
    {
        $category = $categoryRepository->find($id);
        return $this->render('category/detailed-category.html.twig', ['category' => $category]);
    }


    /**
     * @Route("/category/edit/{id}", name="editCategory")
     */
    public function editCategory(Request $request, EntityManagerInterface $em, $id, Category $category)
    {

        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($category);
            $em->flush();

            // Go to success page after editing the category
            return $this->redirectToRoute('success');
        }

        return $this->render('category/edit-category.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @Route("/deleteCategory/{id}", name="deleteCategory")
     */
    function deleteCategory(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $categoryRepository->find($id);

        // If a category has products, the category cannot be delete
        try {
            $em->remove($category);
            $em->flush();

            // Go directly on the page category after removing the category
            $categories = $categoryRepository->findAll();
            return $this->render('category/index.html.twig', ['categories' => $categories]);
        } catch (\Exception $e) {
            return $this->redirectToRoute('error');
        };
    }
}
