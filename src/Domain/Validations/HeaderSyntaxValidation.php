<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\Validations;

use FlexPHP\Generator\Domain\Exceptions\HeaderSyntaxValidationException;
use FlexPHP\Schema\Constants\Keyword;

final class HeaderSyntaxValidation implements ValidationInterface
{
    private array $headers;

    private array $allowedHeaders = [
        Keyword::NAME,
        Keyword::DATATYPE,
        Keyword::CONSTRAINTS,
    ];

    private array $requiredHeaders = [
        Keyword::NAME,
        Keyword::DATATYPE,
    ];

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function validate(): void
    {
        $this->validateAllowedHeaders();

        $this->validateRequiredHeaders();
    }

    private function validateAllowedHeaders(): void
    {
        $notAllowedHeaders = \array_filter($this->headers, fn ($header) => !\in_array($header, $this->allowedHeaders));

        if (!empty($notAllowedHeaders)) {
            throw new HeaderSyntaxValidationException('Unknow headers: ' . \implode(', ', $notAllowedHeaders));
        }
    }

    private function validateRequiredHeaders(): void
    {
        $requiredHeaders = \array_filter(
            $this->requiredHeaders,
            fn ($required) => !\in_array($required, $this->headers)
        );

        if (!empty($requiredHeaders)) {
            throw new HeaderSyntaxValidationException(
                'Required headers not present: ' . \implode(', ', $requiredHeaders)
            );
        }
    }
}
