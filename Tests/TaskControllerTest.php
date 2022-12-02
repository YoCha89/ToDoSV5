<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

class TaskControllerTest extends WebTestCase
{
    //Test response code of list action
    public function testListActionSuccess() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    //Test DOM result of list action
    public function testListAction() {

        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        self::bootKernel();
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $taskList = $taskRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $this->assertSame(
            $taskList,
               $crawler->filter('<h4 class="pull-right">')->count()
        );
    }

//Test response code create action
    public function testCreateActionSuccess()
    {          
        $client = static::createClient();
        $crawler = $client->request('POST', '/tasks/create');

        $crawler = $client->submitForm('Ajouter', [
            'form[title]' => 'TestTitle',
            'form[content]' => 'Test content',
        ]);


        $this->assertResponseIsSuccessful();
    }    

    //Test DB result of create action
    public function testCreateActionDB()
    {          

        $client = static::createClient();
        self::bootKernel();
        $crawler = $client->request('POST', '/tasks/create');
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);  

        $maxId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);
        $newId = $maxId+1;

        $crawler = $client->submitForm('Ajouter', [
            'form[title]' => 'Test title',
            'form[content]' => 'Test content',
        ]);

        $testId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);

        $this->assertEquals($newId, $testId->getId());
    }

    //Test DOM result of create action
    public function testCreateAction()
    {          

        $client = static::createClient();
        self::bootKernel();
        $crawler = $client->request('POST', '/tasks/create');
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);  

        $crawler = $client->submitForm('Ajouter', [
            'form[title]' => 'TestTitle',
            'form[content]' => 'Test content',
        ]);

        $testId = $taskRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);

        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-success" role="alert">')->count()
        );
    }


    //Test response code edit action 
    public function testEditActionSuccess()
    {   

        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 

        $task = $taskRepository->findOneBy(array('title'=>'Test edit'));

        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');

        $crawler = $client->submitForm('Ajouter', [
            'form[title]' => 'Test edit',
            'form[content]' => 'Test content',
        ]);

        $this->assertResponseIsSuccessful();
    }


    //Test DB result of edit action
    public function testEditActionDB()
    {   

        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 

        $task = $taskRepository->findOneBy(array('title'=>'Test edit'));

        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');

        $randString = $this->randString($taskRepository, 'content');

        $crawler = $client->submitForm('Ajouter', [
            'form[title]' => 'Test edit',
            'form[content]' => $randString,
        ]);


        $taskTest = $taskRepository->findOneBy(array('content'=>$randString));

        $this->assertNotNull($taskTest);
    }

    //Test DOM result of edit action
    public function testEditAction()
    {   

        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 

        $task = $taskRepository->findOneBy(array('title'=>'Test edit'));

        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');

        $randString = $this->randString($taskRepository, 'title');

        $crawler = $client->submitForm('Ajouter', [
            'form[title]' => 'Test edit',
            'form[content]' => 'Test content',
        ]);

        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-success" role="alert">')->count()
        );
    }

    //Test response code toggle action
    public function testToggleTaskActionSuccess()
    {
        
        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test content'));
        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');


        $this->assertResponseIsSuccessful();
    }

    //Test DB result toggle action
    public function testToggleTaskActionDB()
    {

        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy(array('content'=>'Test content'));

        $currentToggle = $task->getIsDone();
        if($currentToggle == true){
            $assertDb = false;
        }else{
            $assertDb = true;
        }

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');
        $task = $taskRepository->findOneBy(array('id'=>0));

        $this->{'assert' . ucfirst($assert)}($task->getIsDone());
    }

    //Test DOM result of toggle action
    public function testToggleTaskAction()
    {
        
        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

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

        $currentToggle = $task->getIsDone();
        if($currentToggle == true){
            $glyphycon = 'glyphicon glyphicon-remove';
            $assertHtml = $taskToDo+1;
        }else{
            $glyphycon = 'glyphicon glyphicon-ok';
            $assertHtml = $taskDone+1;
        }

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');

        $this->assertSame(
            $asserHtml,
               $crawler->filter($glyphycon)->count()
        );
    }

    //Test property violation of edit action
    public function propertyViolationEdit(){

        //connection profil pas valide
        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('notOwner');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 
        $task = $taskRepository->findOneBy(array('content'=>'Test Ownership'));

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'form[title]' => 'Test Ownership',
            'form[content]' => 'You don\'t own this',
        ]);

        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-danger" role="alert">')->count()
        );
    } 

    //Test property violation of edit action for admin role
    public function adminViolationEdit(){

        //connection profil pas valide
        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class); 
        $task = $taskRepository->findOneBy(array('content'=>'Anonymous'));

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'form[title]' => 'Anonymous',
            'form[content]' => 'Test Anonymous',
        ]);

        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-danger" role="alert">')->count()
        );
    } 

    //Test property violation of toggle action
    public function propertyViolationToggle(){

        //connection profil pas valide
        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('notOwner');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(array('content'=>'Test Ownership'));

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');

        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-danger" role="alert">')->count()
        );
    } 

    //Test property violation of toggle action for admin role
    public function adminViolationToggle(){

        //connection profil pas valide
        $client = static::createClient();
        self::bootKernel();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(array('content'=>'Anonymous'));

        $crawler = $client->request('GET', '/tasks/'. $task->getId() .'/task_toggle');
        
        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-danger" role="alert">')->count()
        );
    }

    //Function providing random strings the does not macth any DB existing entry for test data
    protected function randString($repo, $unique){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';
        $task = 'notNull';

        while($task != null){
            for ($i = 0; $i < 6; $i++) {
                $randString = $characters[rand(0, strlen($characters))];
            }     

            $task = $repo->findOneBy(array($unique => $randString));       
        }


        return $randString;
    }

    protected function getUser($role){

        self::bootKernel();

        $container = static::getContainer();

        $userRepository = $container->get(UserRepository::class);

        if($role == 'admin'){
            $testUser = $userRepository->findOneByEmail('admin@test.com');
        }elseif($role == 'notOwner'){
            $testUser = $userRepository->findOneByEmail('user@test.com');
        }else{
            $testUser = $userRepository->findOneByEmail('user@test.com');
        }

        return $testUser;
    }
}
