<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: "questions")]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Assert\NotBlank]
    #[ORM\Column(length: 256)]
    private string $question;

    #[ORM\Column(type: Types::JSON)]
    private array $answerOptions = [];

    #[ORM\Column(type: Types::JSON)]
    private array $correctAnswers = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswerOptions(): array
    {
        return $this->answerOptions;
    }

    public function setAnswerOptions(array $answerOptions): self
    {
        $this->answerOptions = $answerOptions;

        return $this;
    }

    public function getCorrectAnswers(): array
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(array $correctAnswers): void
    {
        $this->correctAnswers = $correctAnswers;
    }
}
