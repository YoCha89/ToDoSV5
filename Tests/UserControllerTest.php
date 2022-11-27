<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class UserControllerTest extends WebTestCase
{


//Test response code of list action
    public function testListActionSuccess() {
        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();

    }

//Test DOM result of list action
    public function testListAction() {
        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');

        $userRepository = static::getContainer()->get(UserRepository::class);
        
        $userList = $userRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $this->assertSame(
            $userList,
               $crawler->filter('html:contains(<th scope="row">)')->count()
        );
    }


    //Test response code of edit action
    public function testEditActionSuccess()
    {
        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(array('email'=>'modify'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user_form[email]' => 'modify@fake.com',
            'user_form[username]' => 'modify',
        ]);
        $this->assertResponseIsSuccessful();

    }


    //Test DB result of edit action
    public function testEditActionDb()
    {
        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $randString = $this->randString( $userRepository);

        $user = $userRepository->findOneBy(array('username'=>'editUser'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user_form[email]' => $randString,
            'user_form[username]' => 'editUser',
        ]);

        $userTest = $userRepository->findOneBy(array('email'=> $randString));

        $this->assertNotNull($userTest);
    }


    //Test DOM result of edit action
    public function testEditAction()
    {
        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $randString = $this->randString( $userRepository);

        $user = $userRepository->findOneBy(array('username'=>'editUser'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user_form[email]' => $randString,
            'user_form[username]' => 'editUser',
        ]);

        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
    }

    //Test for user manipulation admin restrictions : list
    public function adminRestrictionList(){
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');
        
        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-danger" role="alert">')->count()
        );        
    }

    //Test for user manipulation admin restrictions : edit
    public function adminRestrictionEdit(){
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(array('email'=>'modify'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user_form[email]' => 'modify@fake.com',
            'user_form[username]' => 'modify',
        ]);      

        $this->assertSame(
            1,
               $crawler->filter('<div class="alert alert-danger" role="alert">')->count()
        );
    }

    //Function providing random strings the does not macth any DB existing entry for test data
    protected function randString($repo){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';
        $user = 'notNull';

        while($user != null){
            for ($i = 0; $i < 6; $i++) {
                $randString = $characters[rand(0, strlen($characters))] . '@fake.com';
            }     

            $user = $repo->findOneBy(array('email' => $randString))       
        }

        retrun $randstring;
    }

    protected function getUser($role){

        $userRepository = static::$container->get(UserRepository::class);

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
