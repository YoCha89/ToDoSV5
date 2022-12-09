<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User {
        $user = new User(); 

        $user->setEmail('email@fake.com');
        $user->setUsername('username');

        return $user;
    }

    //Test that a User that should be valid does not throw erros
    public function assertHasErrors(User $user, int $number = 0){

        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($user);
        $messages = [];

        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    //Test that a User that should be valid does not throw erros
    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    // Test that an error is thrown by the validator if we set a blank username
    public function testInvalidBlankUsername(){
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    // Test that an error is thrown by the validator if we set a blank email
    public function testInvalidBlankEmail(){
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    // Test that an error is thrown by the validator if we set a invalid email
    public function testInvalidInvalidEmail(){
        $this->assertHasErrors($this->getEntity()->setEmail('hello'), 1);
    }
}
