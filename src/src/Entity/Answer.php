<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ORM\Table(name: "answers")]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(type: Types::JSON)]
    private array $answers = [];

    #[ORM\Column(type: Types::JSON)]
    private array $correctAnswers = [];

    #[ORM\Column(type: Types::JSON)]
    private array $incorrectAnswers = [];

    #[ORM\Column]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function setAnswers(array $answers): self
    {
        $this->answers = $answers;

        return $this;
    }

    public function getCorrectAnswers(): array
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(array $correctAnswers): self
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }

    public function getIncorrectAnswers(): array
    {
        return $this->incorrectAnswers;
    }

    public function setIncorrectAnswers(array $incorrectAnswers): self
    {
        $this->incorrectAnswers = $incorrectAnswers;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getCreatedAtFormat(): ?string
    {
        return $this->createdAt?->format('Y-m-d H:i:s');
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
