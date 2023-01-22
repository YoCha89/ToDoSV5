<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\InvalidArgumentException;

class TRegistrationControllerTest extends WebTestCase {

    /*Test response code of indexAction action*/
    public function testRegisterSuccess() {
        $client = static::createClient();
        
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $client->request('POST', '/register');

        $client->submitForm('Register', [
            'registration_form[email]' =>  'test@register.fr',
            'registration_form[username]' =>'registerNew',
            'registration_form[plainPassword]' =>'Test1234register',
            'registration_form[admin]' =>false,
        ]);

        $client->followRedirect('user_list');

        $this->assertResponseIsSuccessful();
    }

    /*Test DOM result of indexAction*/
    public function testRegisterDb() {
        $client = static::createClient();
        
        $user = $this->getUser('admin');
        $client->loginUser($user);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $client->request('GET', '/register');

        $crawler = $client->submitForm('Register', [
            'registration_form[email]' =>  'test@register.fr',
            'registration_form[username]' =>'registerNew',
            'registration_form[plainPassword]' =>'Test1234register',
            'registration_form[admin]' =>false,
        ]);

        $testId1 = $userRepository->findAllOrdered();
        $testId2 = $userRepository->findOneBy(array('username' => 'registerNew'));

        $this->assertEquals($testId1[0]->getId(), $testId2->getId());
    }

    /*Test for user manipulation admin restrictions : register*/
    public function testAdminRestrictionRegister(){
        $client = static::createClient();
        $user = $this->getUser('user');
        $client->loginUser($user);

        $client->request('GET', '/register');

        $this->assertSelectorTextContains('abbr', 'AccessDeniedException'); 
    }

    /*provides the right user for the test*/
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
