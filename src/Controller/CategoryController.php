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
    public function addCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;

        $builder = $this->createFormBuilder();
        $builder->add('name', TextType::class)
        ->add('description', TextType::class)
        ->add('slug', TextType::class)
        ->add('save', SubmitType::class, ['label'=> 'Add category']);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $data = $form->getData();
            // dd($data);
            $category = new Category;
            $category->setName($data['name'])
            ->setDescription($data['description'])
            ->setSlug($data['slug']);

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add-category.html.twig', ['form' => $form->createView(),]);
    }


    /**
     * @Route("/category/edit/{id}", name="editCategory")
     */
    public function editCategory(Request $request, EntityManagerInterface $em, $id)
    {
        $category = $em->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $data = $form->getData();

            $category = new Category;
            $category->setName($data['name'])
            ->setDescription($data['description'])
            ->setSlug($data['slug']);

            $em->persist($category);
            $em->flush();

            // return $this->redirectToRoute('success');
        }

        return $this->render('product/add-category.html.twig', ['form' => $form->createView(),]);
    }
}
