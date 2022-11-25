<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\TaskRepository;

class TaskControllerTest extends WebTestCase
{

    public function testListAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $taskList = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $this->assertResponseIsSuccessful();
        $this->assertSame(
            $taskList,
               $crawler->filter('html:contains(<h4 class="pull-right">)')->count()
        );
    }

    public function testCreateAction()
    {          
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks/create');

        $taskRepository = static::getContainer()->get(TaskRepository::class);  

        $maxId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);
        $newId = $maxId+1;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';

        for ($i = 0; $i < 6; $i++) {
            $randString = $characters[rand(0, strlen($characters))];
        }

        $crawler = $client->submitForm('Ajouter', [
            'task_form[title]' => $randString,
            'task_form[content]' => 'Test content',
        ]);

        $testId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201, $message = '');
        $this->assertEquals($newId, $testId->getId());
        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
    }

    public function testEditAction()
    {   
        $client = static::createClient();

        $taskRepository = static::getContainer()->get(TaskRepository::class); 

        $task = $taskRepository->findOneBy(array('content'=>'Test content'));

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';

        for ($i = 0; $i < 6; $i++) {
            $randString = $characters[rand(0, strlen($characters))];
        }

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'task_form[title]' => $randString,
            'task_form[content]' => 'Test content',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
    }

    public function testToggleTaskAction()
    {
        
        $client = static::createClient();

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test content'));

        $taskList = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $taskDone = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andwhere('t.isDone = true')
            ->getQuery()
            ->getResult();

        $taskToDo = $taskList - $taskDone;

        $title = $task->getTitle();
        $currentToggle = $task->getIsDone();
        if($currentToggle == true){
            $assertDb = false;
            $glyphycon = 'glyphicon glyphicon-remove';
            $assertHtml = $taskToDo+1;
        }else{
            $assertDb = true;
            $glyphycon = 'glyphicon glyphicon-ok';
            $assertHtml = $taskDone+1;
        }

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/delete');
        $task = $taskRepository->findOneBy(array('id'=>0));

        $this->assertResponseIsSuccessful();
        $this->{'assert' . ucfirst($assert)}($task->getIsDone());
        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
        $this->assertSame(
            $asserHtml,
               $crawler->filter($glyphycon)->count()
        );
    }

    public function testDeleteAction()
    {          
        $client = static::createClient();

        $taskRepository = static::getContainer()->get(TaskRepository::class);  

        $task = $taskRepository->findOneBy(array('content'=>'Test content'));

       $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/delete');


        $this->assertResponseIsSuccessful();
        $this->assertSame(
            0,
               $crawler->filter('Test content')->count()
        );
    }

    public function notBlankValidator(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks/create');

        $crawler = $client->submitForm('Ajouter', [
            'task_form[title]' => ' ',
            'task_form[content]' => ' ',
        ]);

        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
    }


    public function notNullValidator(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks/create');

        $crawler = $client->submitForm('Ajouter', [
            'task_form[title]' => null,
            'task_form[content]' => null,
        ]);

        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
    }

    public function propertyViolationEdit(){
        //message html flash d'interdiction
        //présence du bouton html  alors que pas les droits
    } 

        public function propertyViolationToggle(){
        //message html flash d'interdiction
        //présence du bouton html  alors que pas les droits
    } 
}
