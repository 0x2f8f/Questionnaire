<?php

namespace App\Command;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Webmozart\Assert\Assert;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:questionnaire',
    description: 'Questionnaire',
)]
class QuestionnaireCommand extends Command
{
    private const CORRECT_ANSWERS   = 'correct';
    private const INCORRECT_ANSWERS = 'incorrect';
    private SymfonyStyle $io;
    private array $questions = [];

    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly AnswerRepository   $answerRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $username = $this->io->ask(
            'Your name',
            null,
            function (?string $input) {
                Assert::stringNotEmpty($input, 'Name is invalid');
                Assert::maxLength($input, 'Name is long', 128);

                return $input;
            },
        );

        $this->writeln(sprintf('Hello, %s!', $username));
        $this->io->writeln('');
        $this->writeln('Now you will be asked questions. You can give one or more answers, listing them with commas. For example: 1,2');
        $this->writeln('After completing the questionnaire, you will be shown questions that you answered correctly and questions where the answers contained errors.');
        $this->writeln("Let's begin!");

        $this->questionnaire($username);

        return Command::SUCCESS;
    }

    private function writeln(string $message): void
    {
        $this->io->writeln($message);
        sleep(1);
    }

    private function questionnaire(string $username): void
    {
        $questions = $this->getQuestions();
        if (!$questions) {
            $this->io->error('Questions not found!');

            return;
        }
        $questionKeys = array_keys($questions);
        shuffle($questionKeys);
        $userAnswers = [];
        $correctAnswers = [];
        $incorrectAnswers = [];
        $currentAnswers = [
            self::CORRECT_ANSWERS   => [],
            self::INCORRECT_ANSWERS => [],
        ];

        $i = 1;
        foreach ($questionKeys as $key) {
            $question = $questions[$key];
            $res = sprintf('%s. %s',
                $i++,
                $question->getQuestion());
            $message = $res;
            $options = $question->getAnswerOptions();
            foreach ($options as $num => $option) {
                $message = sprintf('%s
    %s. %s',
                    $message,
                    $num,
                    $option);
            }
            $answer = $this->io->ask(
                $message,
                null,
                function (?string $input) {
                    Assert::stringNotEmpty($input, 'Answer value is invalid');

                    return $input;
                },
            );
            $answer = preg_replace('/\s+/', '', $answer);
            $userAnswers[$question->getId()] = $answer;
            $isCorrect = in_array($answer, $question->getCorrectAnswers());
            $currentAnswer = isset($options[$answer])
                ? sprintf('%s (you indicated option %s)', $options[$answer], $answer)
                : sprintf('(you indicated option %s)', $answer);
            if ($isCorrect) {
                $correctAnswers[] = $question->getId();
                $currentAnswers[self::CORRECT_ANSWERS][$res] = $currentAnswer;
            } else {
                $incorrectAnswers[] = $question->getId();
                $currentAnswers[self::INCORRECT_ANSWERS][$res] = $currentAnswer;
            }
        }

        $this->saveUserAnswers(
            username: $username,
            userAnswers: $userAnswers,
            correctAnswers: $correctAnswers,
            incorrectAnswers: $incorrectAnswers,
        );

        $this->printUserResult($currentAnswers);

        $answer = $this->io->ask(
            'Would you like to take the questionnaire again? [y/n] (default: n)',
            'n',
            function (?string $input) {
                Assert::string($input, 'Answer is invalid');

                return $input;
            },
        );
        if ($answer === 'y') {
            $this->questionnaire($username);
        }
    }

    /**
     * @return Question[]
     */
    private function getQuestions(): array
    {
        if (count($this->questions)) {
            return $this->questions;
        }

        $questions = $this->questionRepository->findAll();
        foreach ($questions as $question) {
            $this->questions[$question->getId()] = $question;
        }

        return $this->questions;
    }

    private function saveUserAnswers(
        string $username,
        array  $userAnswers,
               $correctAnswers,
               $incorrectAnswers
    ): void {
        try {
            ksort($userAnswers);
            $answer = new Answer();
            $answer
                ->setUsername($username)
                ->setAnswers($userAnswers)
                ->setCorrectAnswers($correctAnswers)
                ->setIncorrectAnswers($incorrectAnswers);
            $this->answerRepository->save($answer);
        } catch (\Exception $e) {
            $this->io->error(sprintf('Failed to save user answers. Error: %s', $e->getMessage()));
        }
    }

    private function printUserResult(array $currentAnswers): void
    {
        $corrects = $currentAnswers[self::CORRECT_ANSWERS];
        $incorrects = $currentAnswers[self::INCORRECT_ANSWERS];
        $this->io->writeln(sprintf('You answered %s out of %s questions correctly.',
            count($corrects),
            count($corrects) + count($incorrects),
        ));
        $this->io->writeln('');

        if (count($corrects)) {
            $this->io->writeln('<fg=green;options=bold>Correct answers:</>');
            foreach ($corrects as $question => $answer) {
                $this->io->writeln(sprintf('%s <fg=green>%s</>', $question, $answer));
            }
            $this->io->writeln('');
        }

        if (count($incorrects)) {
            $this->io->writeln('<fg=red;options=bold>Incorrect answers:</>');
            foreach ($incorrects as $question => $answer) {
                $this->io->writeln(sprintf('%s <fg=red>%s</>', $question, $answer));
            }
            $this->io->writeln('');
        }
    }
}
