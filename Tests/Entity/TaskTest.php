<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task {
        return (new Task())
            ->setTitle('Title of task')
            ->setContent('Description of task');
    }

    //Test that a Task that should be valid does not throw erros
    public function assertHasErrors(Task $task, int $number = 0){

        self::bootKernel();
        $errors = self::$container->get('validator')->validate($task);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    // Test that an error is thrown by the validator if we set a blank title
    public function testInvalidBlankTitle(){
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    // Test that an error is thrown by the validator if we set a blank content
    public function testInvalidBlankContent(){
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }
}