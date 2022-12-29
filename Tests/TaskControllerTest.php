<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

class TaskControllerTest extends WebTestCase
{
   //Test response code of list action
    public function testListTaskActionSuccess() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    //Test DOM result of list action
    public function testListTaskActionDom() {

        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $taskList = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $this->assertCount($taskList[0][1], $crawler->filter('.caption'));
    }

//Test response code create action
   public function testCreateActionSuccess()
    {          
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);
        $crawler = $client->request('POST', '/tasks/create');

        $crawler = $client->submitForm('Ajouter', [
            'task[title]' => 'TestTitle',
            'task[content]' => 'Test create',
        ]);

        $crawler = $client->followRedirect('task_list');
        $this->assertResponseIsSuccessful();
    }    

    //Test DB result of create action
    public function testCreateActionDB()
    {          
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);
        $crawler = $client->request('POST', '/tasks/create');


        $taskRepository = static::getContainer()->get(TaskRepository::class);  

        $randString = $this->randString($taskRepository);

        $crawler = $client->submitForm('Ajouter', [
            'task[title]' =>  $randString,
            'task[content]' =>'Test create',
        ]);

        $testId1 = $taskRepository->findAllOrdered();
        $testId2 = $taskRepository->findOneBy(array('title' => $randString));

        $this->assertEquals($testId1[0]->getId(), $testId2->getId());
    }

    //Test DOM result of create action
    public function testCreateActionDom()
    {          

        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);
        $crawler = $client->request('POST', '/tasks/create');

        $taskRepository = static::getContainer()->get(TaskRepository::class); 
        $randString = $this->randString($taskRepository);  

        $crawler = $client->submitForm('Ajouter', [
            'task[title]' => $randString,
            'task[content]' => 'Test create',
        ]);

        $crawler = $client->followRedirect('task_list');
        $this->assertSelectorTextContains('h4', $randString);
    }

    //Test response code edit action 
    public function testEditTaskActionSuccess()
    {   
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test success'));

        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');
        // dd($crawler);
        $crawler = $client->submitForm('Modifier', [
            'task[title]' => 'Test success',
            'task[content]' => 'Test success',
        ]);

        $crawler = $client->followRedirect('task_list');
        $this->assertResponseIsSuccessful();
    }


    //Test DB result of edit action
    public function testEditTaskActionDB()
    {   
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 

        $task = $taskRepository->findOneBy(array('title'=>'Test db'));
        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');

        $randString = $this->randString($taskRepository);

        $crawler = $client->submitForm('Modifier', [
            'task[title]' => 'Test db',
            'task[content]' => $randString,
        ]);

        $crawler = $client->followRedirect('task_list');
        $taskTest = $taskRepository->findOneBy(array('content'=>$randString));

        $this->assertNotNull($taskTest);
    }

    //Test DOM result of edit action
    public function testEditTaskActionDom()
    {   

        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 

        $task = $taskRepository->findOneBy(array('content'=>'Test DOM'));

        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');

        $randString = $this->randString($taskRepository);

        $crawler = $client->submitForm('Modifier', [
            'task[title]' => $randString,
            'task[content]' => 'Test DOM',
        ]);

        $crawler = $client->followRedirect('task_list');
        $this->assertSelectorTextContains('h4', $randString);
    }

    //Test response code toggle action
    public function testToggleTaskActionSuccess()
    {
        
        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test toggle'));
        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/toggle');

        $crawler = $client->followRedirect('task_list');
        $this->assertResponseIsSuccessful();
    }

    //Test DB result toggle action
    public function testToggleTaskActionDB()
    {

        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test toggle'));

        $currentToggle = $task->isDone();
        if($currentToggle == true){
            $assert = false;
        }else{
            $assert = true;
        }

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/toggle');

        if($assert == false){
            $this->assertFalse($task->isDone());
        }else{
            $this->assertTrue($task->isDone());
        }
    }

    //Test DOM result of toggle action
    public function testToggleTaskActionDom()
    {
        
        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test toggle'));

        $taskToDo = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andwhere('t.isDone = false')
            ->getQuery()
            ->getResult();

        $taskDone = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andwhere('t.isDone = true')
            ->getQuery()
            ->getResult();

        $currentToggle = $task->isDone();
        if($currentToggle == true){
            $glyphycon = '.glyphicon-remove';
            $assertHtml = $taskToDo[0][1]+1;
        }else{
            $glyphycon = '.glyphicon-ok';
            $assertHtml = $taskDone[0][1]+1;
        }

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/toggle');

        $crawler = $client->followRedirect('task_list');
        $this->assertCount($assertHtml, $crawler->filter($glyphycon));
    }

    //Test property violation of edit action
    public function testPropertyViolationEdit(){

        //connection profil pas valide
        $client = static::createClient();
        
        $user = $this->getUser('notOwner');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 
        $task = $taskRepository->findOneBy(array('title'=>'Test Ownership'));

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $client->followRedirect('task_list');
        // dd($client->getResponse()->getContent());
        $this->assertCount(1, $crawler->filter('.alert-danger'));
    } 

    //Test property violation of edit action for admin role
    public function testAdminViolationEdit(){

        //connection profil pas valide
        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 
        $task = $taskRepository->findOneBy(array('content'=>'Test Anonymous'));

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'task[title]' => 'Anonymous',
            'task[content]' => 'Test Anonymous',
        ]);
        $client->followRedirect('task_list');

        $this->assertCount(1, $crawler->filter('.alert-danger'));
    } 

    //Test property violation of toggle action
    public function testPropertyViolationToggle(){

        //connection profil pas valide
        $client = static::createClient();
        
        $user = $this->getUser('notOwner');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(array('content'=>'Test Ownership'));

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');

        $client->followRedirect('task_list');
        $this->assertCount(1, $crawler->filter('.alert-danger'));
    } 

    //Test property violation of toggle action for admin role
    public function testAdminViolationToggle(){

        //connection profil pas valide
        $client = static::createClient();
        
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(array('content'=>'Test Anonymous'));

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');
        
        $client->followRedirect('task_list');
        $this->assertCount(1, $crawler->filter('.alert-danger'));
    }

    //Function providing random strings the does not macth any DB existing entry for test data
    protected function randString($repo){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = 'test';
        $task = 'notNull';

        while($task != null){
            for ($i = 0; $i <= 6; $i++) {
                $randString = $randString . $characters[rand(0, strlen($characters)-1)];
            }     
            // $randString = $randString . '\@fake\.com';
            $task = $repo->findOneBy(array('content' => $randString));     
        }

        return trim($randString);
    }

    protected function getUser($role){

        $userRepository = static::getContainer()->get(UserRepository::class);

        if($role == 'admin'){
            $testUser = $userRepository->findOneBy(array('email' => 'admin@test.com'));
        }elseif($role == 'notOwner'){
            $testUser = $userRepository->findOneBy(array('email' => 'noOwner@test.com'));
        }else{
            $testUser = $userRepository->findOneBy(array('email' => 'user@test.com'));
        }

        return $testUser;
    }
}
