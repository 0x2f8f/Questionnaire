<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getQuestions() as $questionData) {
            $question = new Question();
            $question
                ->setQuestion($questionData['question'])
                ->setAnswerOptions($questionData['answer_options'])
                ->setCorrectAnswers($questionData['correct_answers']);
            $manager->persist($question);
        }

        $manager->flush();
    }

    private function getQuestions(): array
    {
        return [
            [
                'question'        => '1 + 1 =',
                'answer_options'  => [
                    '1' => '3',
                    '2' => '2',
                    '3' => '0',
                ],
                'correct_answers' => [
                    '2',
                ],
            ],
            [
                'question'        => '2 + 2 =',
                'answer_options'  => [
                    '1' => '4',
                    '2' => '3 + 1',
                    '3' => '10',
                ],
                'correct_answers' => [
                    '1',
                    '2',
                    '1,2',
                ],
            ],
            [
                'question'        => '3 + 3 =',
                'answer_options'  => [
                    '1' => '1 + 5',
                    '2' => '1',
                    '3' => '6',
                    '4' => '2 + 4',
                ],
                'correct_answers' => [
                    '1',
                    '3',
                    '4',
                    '1,3',
                    '1,4',
                    '3,4',
                    '1,3,4',
                ],
            ],
            [
                'question'        => '4 + 4 =',
                'answer_options'  => [
                    '1' => '8',
                    '2' => '4',
                    '3' => '0',
                    '4' => '0 + 8',
                ],
                'correct_answers' => [
                    '1',
                    '4',
                    '1,4',
                ],
            ],
            [
                'question'        => '5 + 5 =',
                'answer_options'  => [
                    '1' => '6',
                    '2' => '18',
                    '3' => '10',
                    '4' => '9',
                    '5' => '0',
                ],
                'correct_answers' => [
                    '3',
                ],
            ],
            [
                'question'        => '6 + 6 =',
                'answer_options'  => [
                    '1' => '3',
                    '2' => '9',
                    '3' => '0',
                    '4' => '12',
                    '5' => '5 + 7',
                ],
                'correct_answers' => [
                    '4',
                    '5',
                    '4,5',
                ],
            ],
            [
                'question'        => '7 + 7 =',
                'answer_options'  => [
                    '1' => '5',
                    '2' => '14',
                ],
                'correct_answers' => [
                    '2',
                ],
            ],
            [
                'question'        => '8 + 8 =',
                'answer_options'  => [
                    '1' => '16',
                    '2' => '12',
                    '3' => '9',
                    '4' => '5',
                ],
                'correct_answers' => [
                    '1',
                ],
            ],
            [
                'question'        => '9 + 9 =',
                'answer_options'  => [
                    '1' => '18',
                    '2' => '9',
                    '3' => '17 + 1',
                    '4' => '2 + 16',
                ],
                'correct_answers' => [
                    '1',
                    '3',
                    '4',
                    '1,3',
                    '1,4',
                    '3,4',
                    '1,3,4',
                ],
            ],
            [
                'question'        => '10 + 10 =',
                'answer_options'  => [
                    '1' => '0',
                    '2' => '2',
                    '3' => '8',
                    '4' => '20',
                ],
                'correct_answers' => [
                    '4',
                ],
            ],
        ];
    }
}
