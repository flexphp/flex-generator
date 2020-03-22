<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\CreateEntityRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreateEntityResponse;
use FlexPHP\Generator\Domain\UseCases\CreateEntityUseCase;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\UseCases\Exception\NotValidRequestException;

class CreateEntityUseCaseTest extends TestCase
{
    public function testItNotValidRequestThrowException(): void
    {
        $this->expectException(NotValidRequestException::class);

        $useCase = new CreateEntityUseCase();
        $useCase->execute(null);
    }

    /**
     * @dataProvider getEntityFile()
     */
    public function testItSymfony43Ok(string $entity, array $properties): void
    {
        $request = new CreateEntityRequest($entity, $properties);

        $useCase = new CreateEntityUseCase();
        $response = $useCase->execute($request);

        $this->assertInstanceOf(CreateEntityResponse::class, $response);
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
                    ],
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
