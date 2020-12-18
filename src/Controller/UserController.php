<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="addUser", methods={"GET","POST"})
     */
    public function addUser(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {

        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {

                $user = $form->getData();
                $roles = $form->get('roles')->getData();
    
                $user->setRoles([0 => $roles]);
    
                $plainPassword = $form['password']->getData();
    
                if(trim($plainPassword) != ''){
                    $password = $passwordEncoder->encodePassword($user, $plainPassword);
                    $user->setPassword($password);
    
                }
                $em->persist($user);
                $em->flush();
    
                // Go directly on the page users after removing the users
                $users = $userRepository->findAll();
                return $this->render('user/index.html.twig', ['users' => $users]);
            }
        } catch (\Throwable $th) {
            $this->addFlash('notice', 'Votre mot de passe semble incorrect !');
        }

        return $this->render('user/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="showUser", methods={"GET"})
     */
    public function showUser(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="editUser", methods={"GET","POST"})
     */
    public function editUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="deleteUser", methods={"DELETE"})
     */
    public function deleteUser(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

}

