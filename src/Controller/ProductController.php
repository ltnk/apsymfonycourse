<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use App\Repository\CategoryRepository;
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
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
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
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add-product.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/detailed-product/{id}", name="detailedProducts")
     */
    function detailedProducts(ProductRepository $productRepository, Request $request, EntityManagerInterface $em, $id)
    {
        $product = $productRepository->find($id);
        return $this->render('product/detailed-product.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/edit/{id}", name="editProduct")
     */
    public function editProduct(Request $request, EntityManagerInterface $em, $id, Product $product)
    {
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('success');
        }

        return $this->render('category/edit-category.html.twig', ['form' => $form->createView(),]);
     
    }

    /**
     * @Route("/category/{id}/all", name="showAllProductsOneCategory")
     */
    function showAllProductsOneCategory(CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, $id)
    {
        $products = $productRepository->findBy(array('category' => $id));
        return $this->render('product/all-products.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/deleteProduct/{id}", name="deleteProduct")
     */
    function deleteProduct(ProductRepository $productRepository, Request $request, EntityManagerInterface $em, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $productRepository->find($id);

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('success');
    }
}
