<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class UserControllerTest extends WebTestCase
{

    public function testListAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $userRepository = static::getContainer()->get(UserRepository::class);
        
        $userList = $userRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();

        $this->assertSame(
            $userList,
               $crawler->filter('html:contains(<th scope="row">)')->count()
        );
        $this->assertResponseIsSuccessful();

    }

 /*   public function testCreateAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->assertResponseIsSuccessful();

    }*/

    public function testEditAction()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';

        for ($i = 0; $i < 6; $i++) {
            $randString = $characters[rand(0, strlen($characters))];
        }

        $user = $userRepository->findOneBy(array('email'=>'Test email'));

        $crawler = $client->request('GET', '/users/'.$user->getId().'/edit');
        $crawler = $client->submitForm('Modifier', [
            'user_form[email]' => $randString . '@fake.com',
            'user_form[username]' => 'Testname',
        ]);

        $this->assertSame(
            1,
               $crawler->filter($randString)->count()
        );
        $this->assertResponseIsSuccessful();

    }

    public function testDeleteAction()
    {          
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class); 

        $user = $taskRepository->findOneBy(array('content'=>'Test content'));

       $crawler = $client->request('GET', '/users/'. $user->getId() .'/delete');


        $this->assertResponseIsSuccessful();
        $this->assertSame(
            0,
               $crawler->filter('Test content')->count()
        );
    }

/*
    public function mailValidator(){

    }
*/

    public function adminRestriction(){
        
    }
}
