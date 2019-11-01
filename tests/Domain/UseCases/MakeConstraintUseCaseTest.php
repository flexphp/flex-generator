<?php

namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\MakeConstraintRequest;
use FlexPHP\Generator\Domain\UseCases\MakeConstraintUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

class MakeConstraintUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException()
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new MakeConstraintUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     * @param string $entity
     * @param string $properties
     * @return void
     */
    public function testItSymfony43Ok(string $entity, array $properties): void
    {
        $request = new MakeConstraintRequest($entity, $properties);

        $useCase = new MakeConstraintUseCase();
        $response = $useCase->execute($request);

        $file = $response->file;
        $this->assertFileExists($file);
        \unlink($file);
    }

    public function getEntityFile(): array
    {
        return [
            ['Posts', [
                'title' => [
                    'Name' => 'Title',
                    'DataType' => 'string',
                    'Type' => 'text',
                    'Constraints' => [
                        'required' => true,
                    ]
                ],
                'content' => [
                    'Name' => 'Content',
                    'DataType' => 'varchar',
                    'Type' => 'textarea',
                    'Constraints' => [
                        'required',
                        'length' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                ],
            ]],
            ['Comments', [
                'comment' => [
                    'Name' => 'Comment',
                    'DataType' => 'varchar',
                    'Constraints' => [
                        'length' => [
                            'min' => 5,
                            'max' => 50,
                        ],
                    ],
                ],
                'createdAt' => [
                    'Name' => 'Created At',
                    'DataType' => 'datetime',
                    'Type' => 'text',
                    'Constraints' => [
                        'type' => 'date',
                    ],
                ],
            ]],
        ];
    }
}
