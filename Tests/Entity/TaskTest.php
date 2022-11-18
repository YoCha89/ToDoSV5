<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Controller\TaskController;
use App\Repository\TaskRepository;
use App\Entity\User;
use App\Form\TaskType;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;


class TaskTest extends TestCase
{
    /* public function testListAction(EntityManagerInterface $em)
    {
       $taskController = new TaskController();
        $taskRepository = new TaskRepository();

        $taskList = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $this->assertEquals($taskList, );
    }*/


   /* public function testCreateAction()
    {            

        $taskController = new TaskController();
        $taskRepository = new TaskRepository();

        $maxId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);
        $newId = $maxId+1;


        //formDependencie with random content
        $taskController->createAction();

        $testId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);

        $this->assertEquals($newId, $testId->getId());
    }*/

   /* public function notBlankValidator(){

    }

    public function notNullValidator(){
        
    }

    public function constraintProvider()
    {
        return [
            ['title', null, 'null'],
            ['content', null, 'null'],
            ['title', '', 'notBlank'],
            ['content', '', 'notBlank'],
        ];
    }*/

    /**
     * @dataProvider editProvider
     */
   /* public function testEditAction()
    {   
        $taskController = new TaskController();
        $taskRepository = new TaskRepository();

        $id = $a;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';

        for ($i = 0; $i < 6; $i++) {
            $randString = $characters[rand(0, strlen($characters))];
        }

        //formDependencie with $b guidance
        // $taskController->editAction(//form)


        $task = $taskRepository->findOneBy(array('id'=>$a));

        $this->assertEquals($randString, $task->{'get' . ucfirst($b)});
    
    }
    public function editProvider()
    {
        return [
            [0, 'title'],
            [0, 'content'],
            [1, 'title'],
            [1, 'content'],
            [2, 'title'],
            [2, 'content'],
        ];
    }*/

    /**
     * @dataProvider toggleProvider
     */
    public function testToggleTaskAction($a)
    {
        $taskController = new TaskController($repo, $em);
        $taskRepository = new TaskRepository();

        $task = $taskRepository->findOneBy(array('id'=>$a));
        $currentToggle = $task->getIsDone();
        if($currentToggle == true){
            $assert = false;
        }else{
            $assert == true;
        }

        $taskController->testToggleTaskAction($task);
        $task = $taskRepository->findOneBy(array('id'=>$a));

        $this->{'assert' . ucfirst($assert)}($task);
    }

    public function toggleProvider()
    {
        return [
            [0],
            [1],
            [2],
            // [3]
        ];
    }
}
