<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{

    public function getEntity(): Task {

        $task = new Task();
        $task->setTitle('Title of task');
        $task->setContent('Description of task');

        return $task;
    }

    //Called by lour different test functions to validate entity or checking the right error
    public function assertHasErrors(Task $task, int $number = 0) {
        
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($task);
        $messages = [];

        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    //Test that a Task that should be valid does not throw erros
    public function testValidEntity()
    {
        $entity = $this->getEntity();
        $this->assertHasErrors($entity, 0);
    }

    // Test that an error is thrown by the validator if we set a blank title
    public function testInvalidBlankTitle(){
        $entity = $this->getEntity();
        $entity->setTitle('');
        $this->assertHasErrors($entity, 1);
    }

    // Test that an error is thrown by the validator if we set a blank content
    public function testInvalidBlankContent(){
        $entity = $this->getEntity();
        $entity->setContent('');
        $this->assertHasErrors($entity, 1);
    }
}
