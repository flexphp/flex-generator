<?php

namespace FlexPHP\Generator\Tests\Domain\UseCases;

use FlexPHP\Generator\Domain\Exceptions\FormatNotSupportedException;
use FlexPHP\Generator\Domain\Messages\Requests\ProcessFormatRequest;
use FlexPHP\Generator\Domain\UseCases\ProcessFormatUseCase;
use FlexPHP\Generator\Tests\TestCase;

class ProcessFormatUseCaseTest extends TestCase
{
    public function testItFormatNotSupportedThrowException()
    {
        $this->expectException(FormatNotSupportedException::class);

        $request = new ProcessFormatRequest('/fake/path/file.doc', 'doc');

        $useCase = new ProcessFormatUseCase();
        $useCase->execute($request);
    }
}
