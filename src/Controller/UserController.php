<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    public function __construct(private UserRepository $repo, private EntityManagerInterface $em)
    {

    }
    
    /*Access to users*/
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('user/list.html.twig', ['users' => $this->repo->findAllButAnonymous()]);
        }else{
            $this->addFlash('error', 'Seul un administrateur peut accéder aux utilisateurs.');
            return $this->redirectToRoute('task_list');
        } 
        
    }

    /*update users and checks property constraints. Admin has all the rights, non admin users must own the task.*/
    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        if($this->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setUpdatedAt(new \Datetime());
                
                $this->em->persist($user);

                $this->em->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");

                return $this->redirectToRoute('user_list');
            }

            return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);            
        }else{
            $this->addFlash('error', 'Seul un administrateur mettre à jour un utilisateur.');
            return $this->redirectToRoute('task_list');
        } 
    }
}
