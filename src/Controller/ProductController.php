<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormFactoryInterface;


class ProductController extends AbstractController
{

    /**
     * @Route("/product", name="products")
     */
    function showProducts(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/product/add", name="addProducts")
     */
    function addProducts(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product;

        $builder = $this->createFormBuilder();
        $builder->add('name', TextType::class)
        ->add('price', IntegerType::class)
        ->add('slug', TextType::class)
        ->add('category', EntityType::class,
        [
            'class' =>Category::class,
            'choice_label' => 'Name',
            'placeholder' =>'Choose a category',
            'label' => 'Category',
        ]
        )
        ->add('save', SubmitType::class, ['label'=> 'Add product']);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $data = $form->getData();
            // dd($data);
            $product = new Product;
            $product->setName($data['name'])
            ->setPrice($data['price'])
            ->setSlug($data['slug'])
            ->setCategory($data['category']);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add-product.html.twig', ['form' => $form->createView(),]);
    }
}
