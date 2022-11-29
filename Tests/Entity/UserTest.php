<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): Task {
        return (new User())
            ->setEmail('email@fake.com')
            ->setUsername('username');
    }

    //Test that a User that should be valid does not throw erros
    public function assertHasErrors(User $user, int $number = 0){

        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
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
