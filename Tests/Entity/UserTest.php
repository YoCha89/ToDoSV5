<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Controller\UserController;
use App\Repository\UserRepository;
use App\Form\UserType;


class UserTest extends TestCase
{
    public function __construct(private UserRepository $repo, private EntityManagerInterface $em)
    {

    }

    public function testListAction()
    {
        
    }

    public function testCreateAction(Request $request)
    {
        $userController = new UserController();
        $userRepository = new UserRepository();

        $maxId = $userRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);
        $newId = $maxId+1;


        //formDependencie with random content
        $userController->createAction();

        $testId = $userRepository->findBy(array('id'>=0), array('id'=>'DESC'), 1);

        $this->assertEquals($newId, $testId->getId());
    }

    public function mailValidator(){

        $this->assertThat(
          $mail,
          $this->PCREMatch(
            '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/'  
          )
        );
    }

    /**
     * @dataProvider constraintProvider
     */
    public function notBlankValidator(){

    }

    /**
     * @dataProvider constraintProvider
     */
    public function notNullValidator(){
        
    }


    public function constraintProvider()
    {
        return [
            ['mail', null, 'null'],
            ['username', null, 'null'],
            ['mail', '', 'notBlank'],
            ['username', '', 'notBlank'],
        ];
    }


    /**
     * @dataProvider editProvider
     */
    public function testEditAction()
    {
        $id = $a;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';

        if($b == 'username'){
            for ($i = 0; $i < 6; $i++) {
                $randString = $characters[rand(0, strlen($characters))];
            }
        }else{  
            for ($i = 0; $i < 4; $i++) {
                $randString1 = $characters[rand(0, strlen($characters))];
            }
            for ($i = 0; $i < 4; $i++) {
                $randString2 = $characters[rand(0, strlen($characters))];
            }

            $randstring = $randString1 . '@' . $randString2 . '.com';
        }

        //formDependencie with $b guidance
        $userController->editAction(//form)

        $user = $userRepository->findOneBy(array('id'=>$a));

        $this->assertEquals($randString, $user->{'get' . ucfirst($b)});
    }
    public function editProvider()
    {
        return [
            [0, 'email'],
            [0, 'username'],
            [1, 'email'],
            [1, 'username'],
            [2, 'email'],
            [2, 'username'],
        ];
    }

    public function adminRestriction(){
        
    }
}
