<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\TaskRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\Mapping\Annotation;
use Doctrine\ORM\Mapping\GeneratedValue;



#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private $createdAt;

    #[ORM\Column(type: Types::STRING)]
    private $title;


    #[Assert\NotBlank(message:"Vous devez saisir du contenu.")]
    #[ORM\Column(type: Types::TEXT)]
    private $content;

    #[ORM\Column(type: Types::BOOLEAN)]
    private $isDone;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function isDone()
    {
        return $this->isDone;
    }

    public function toggle($flag)
    {
        $this->isDone = $flag;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
