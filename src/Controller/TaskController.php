<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;


class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $repo, private EntityManagerInterface $em)
    {
        
    }

    //Access to tasks
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->repo->findAll()]);
    }

    //Create tasks and checks property constraints
    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($this->getUser() != null){
                $task->setUser($this->getUser());
            }else{
                $task->setUser($this->em->getRepository(User::class)->findOneBy(array('username'=>'anonymous')));
            }
           
            $this->em->persist($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);            
        

    }

    //update tasks and checks property constraints. Admin has all the rights, non admin users must own the task.
    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        if($task->getUser()->getUsername() != 'anonymous' && $task->getUser() == $this->getUser()){
            $go = '';
        }elseif($this->isGranted('ROLE_ADMIN')){
            $go = '';
        }

        if(isset($go)){
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('task_list');
            }

            return $this->render('task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);        
        }else{
            $this->addFlash('error', 'Seul l\'utilisateur dédié ou un administrateur peut éditer une tâche.');
        }
    
    }

    //toggle tasks executionand checks property constraints.  Admin has all the rights, non admin users must own the task.
    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        if($task->getUser()->getUsername() != 'anonymous' && $task->getUser() == $this->getUser()){
            $go = '';
        }elseif($this->isGranted('ROLE_ADMIN')){
            $go = '';
        }

        if(isset($go)){

            $task->toggle(!$task->isDone());
            $this->em->flush();

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

            return $this->redirectToRoute('task_list');
    
        }else{
            $this->addFlash('error', 'Seul l\'utilisateur dédié ou un administrateur peut éditer une tâche.');
        }
    }

    //delete tasks and checks property constraints.  Admin has all the rights, non admin users must own the task.
    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        if($task->getUser()->getUsername() != 'anonymous' && $task->getUser() == $this->getUser()){
            $go = '';
        }elseif($this->isGranted('ROLE_ADMIN')){
            $go = '';
        }

        if(isset($go)){
            $this->em->remove($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('task_list');
        }else{
            $this->addFlash('error', 'Seul l\'utilisateur dédié ou un administrateur peut supprimer une tâche.');
        } 
    }
}
