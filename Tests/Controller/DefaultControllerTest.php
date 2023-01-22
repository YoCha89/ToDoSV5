<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\InvalidArgumentException;

class DefaultControllerTest extends WebTestCase {

/*Test response code of indexAction action*/
    public function testindexActionSuccess() {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

/*Test DOM result of indexAction*/
    public function testindexAction() {

        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }
}
