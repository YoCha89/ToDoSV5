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
        
        $userList = $userRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andwhere('u.email != :email')
            ->setParameter(':email', 'anonymous')
            ->getQuery()
            ->getResult();

        //adapter balise CSS
        $this->assertCount($userList[0][1], $crawler->filter('.btn-success'));
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
            'user[email]' => 'modify@fake.com',
            'user[username]' => 'modify',
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
            'user[email]' => $randString,
            'user[username]' => 'editUser',
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
            'user[email]' => $randString,
            'user[username]' => 'editUser',
        ]);

        $this->assertCount(1, $crawler->filter($randString));
    }

    //Test for user manipulation admin restrictions : list
    public function testAdminRestrictionList(){
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');
         
        $this->assertCount(1, $crawler->filter('.alert-danger'));     
    }

    //Test for user manipulation admin restrictions : edit
    public function testAdminRestrictionEdit(){

        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(array('email'=>'modify'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user[email]' => 'modify@fake.com',
            'user[username]' => 'modify',
        ]);      

        $this->assertCount(1, $crawler->filter('.alert-danger'));
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

            $user = $repo->findOneBy(array('email' => $randString));     
        }

        return $randString;
    }

    protected function getUser($role){

        
        $userRepository = static::getContainer()->get(UserRepository::class);

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
