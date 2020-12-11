<?php

namespace App\Controller;

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    function addProducts(KernelInterface $appKernel, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $path = $appKernel->getProjectDir() . '/public';

        $product = new Product;
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug($slugger->slug($product->getName()));

            $file = $form['img']->getData();

            if ($file) {
                // récup nom de fichier sans extension
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();
                // set nom dans la propriété Img
                $product->setImg($newFilename);

                //Déplacer le fichier dans le répertoire public + sous répertoire
                try {
                    $file->move($path, $newFilename);
                } catch (FileException $e) {
                    echo $e->getMessage();
                }
            }

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add-product.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/detailed-product/{id}", name="detailedProducts")
     */
    function detailedProducts(SluggerInterface $slugger, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, $id)
    {
        $product = $productRepository->find($id);
        return $this->render('product/detailed-product.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/edit/{id}", name="editProduct")
     */
    public function editProduct(SluggerInterface $slugger, KernelInterface $appKernel, Request $request, EntityManagerInterface $em, $id, Product $product)
    {
        $path = $appKernel->getProjectDir() . '/public';

        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug($slugger->slug($product->getName()));

            $file = $form['img']->getData();

            if ($file) {
                // récup nom de fichier sans extension
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();
                // set nom dans la propriété Img
                $product->setImg($newFilename);

                //Déplacer le fichier dans le répertoire public + sous répertoire
                try {
                    $file->move($path, $newFilename);
                } catch (FileException $e) {
                    echo $e->getMessage();
                }
            }

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/edit-product.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @Route("/category/{id}/all", name="showAllProductsOneCategory")
     */
    function showAllProductsOneCategory(ProductRepository $productRepository, $id)
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
