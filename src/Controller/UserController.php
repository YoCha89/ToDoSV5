<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    public function __construct(private UserRepository $repo, private EntityManagerInterface $em)
    {

    }
    
    //Access to users
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('user/list.html.twig', ['users' => $this->repo->findAll()]);
        }else{
            $this->addFlash('error', 'Seul un administrateur peut accéder aux utilisateurs.');
        } 
        
    }

    //Create users and checks property constraints
    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        if($this->isGranted('ROLE_ADMIN')){
            $user = new User();
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash('success', "L'utilisateur a bien été ajouté.");

                return $this->redirectToRoute('user_list');
            }

            return $this->render('user/create.html.twig', ['form' => $form->createView()]);            
        }else{
            $this->addFlash('error', 'Seul un administrateur peut créer un utilisateur.');
        } 

    }

    //update users and checks property constraints. Admin has all the rights, non admin users must own the task.
    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        if($this->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->em->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");

                return $this->redirectToRoute('user_list');
            }

            return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);            
        }else{
            $this->addFlash('error', 'Seul un administrateur mettre à jour un utilisateur.');
        } 

    }

    //delete tasks and checks property constraints.  Admin has all the rights, non admin users must own the task.
    /**
     * @Route("/users/{id}/delete", name=users_delete")
     */
    public function deleteUserAction(User $user)
    {
        if($this->isGranted('ROLE_ADMIN')){
            $this->em->remove($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('task_list');
        }else{
            $this->addFlash('error', 'Seul un administrateur peut supprimer un utilisateur.');
        } 
    }
}
