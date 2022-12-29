<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\InvalidArgumentException;

class UserControllerTest extends WebTestCase {

//Test response code of list action
    public function testListUserActionSuccess() {
        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
    }

//Test DOM result of list action
    public function testListUserAction() {

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
    public function testEditUserActionSuccess()
    {

        $client = static::createClient();
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(array('email'=>'edit@test.com'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user[email]' => 'edit@test.com',
            'user[username]' => 'editUser',
        ]);
        $this->assertResponseIsSuccessful();

    }


    //Test DB result of edit action
    public function testEditUserActionDb()
    {

        $client = static::createClient();
        
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $randString = $this->randString($userRepository);

        $user = $userRepository->findOneBy(array('email'=>'edit@test.com'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user[email]' => 'edit@test.com',
            'user[username]' => $randString,
            'user[plainPassword]' => 'abcd1234',
        ]);

        var_dump($randString);

        $userTest = $userRepository->findOneBy(array('username'=> $randString));

        $this->assertNotNull($userTest);
    }


    //Test DOM result of edit action
    public function testEditUserActionDom()
    {

        $client = static::createClient();
        
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $randString = $this->randString($userRepository);

        $user = $userRepository->findOneBy(array('email'=>'edit@test.com'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user[email]' => 'edit@test.com',
            'user[username]' => $randString,
            'user[plainPassword]' => 'abcd1234',
        ]);

        $crawler = $client->followRedirect('user_list');
        
        $this->assertSelectorTextContains('td', $randString);
    }

    //Test for user manipulation admin restrictions : list
    public function testAdminRestrictionList(){
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');

        $this->assertSelectorTextContains('abbr', 'AccessDeniedException'); 
    }

    //Test for user manipulation admin restrictions : edit
    public function testAdminRestrictionEdit(){

        $client = static::createClient();
        $userConnect = $this->getUser('user');
        $client->loginUser($userConnect);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(array('email'=>'edit@test.com'));

        $crawler = $client->request('POST', '/users/'.$user->getId().'/edit');

        $this->assertSelectorTextContains('abbr', 'AccessDeniedException');
    }

    //Function providing random strings the does not macth any DB existing entry for test data
    protected function randString($repo){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';
        $user = 'notNull';

        while($user != null){
            for ($i = 0; $i <= 6; $i++) {
                $randString = $randString . $characters[rand(0, strlen($characters))];
            }     
            // $randString = $randString . '\@fake\.com';
            $user = $repo->findOneBy(array('username' => $randString));     
        }

        return trim($randString);
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
