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

use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Domain\Exceptions\HeaderSyntaxValidationException;

class HeaderSyntaxValidation implements ValidationInterface
{
    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $allowedHeaders = [
        Keyword::NAME,
        Keyword::DATA_TYPE,
        Keyword::CONSTRAINTS,
    ];

    /**
     * @var array
     */
    protected $requiredHeaders = [
        Keyword::NAME,
        Keyword::DATA_TYPE,
    ];

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function validate(): void
    {
        $notAllowedHeaders = [];
        $requiredHeadersNotPresent = [];

        foreach ($this->headers as $header) {
            if (!\in_array($header, $this->allowedHeaders)) {
                $notAllowedHeaders[] = $header;
            }
        }

        if (!empty($notAllowedHeaders)) {
            throw new HeaderSyntaxValidationException('Unknow headers: ' . \implode(', ', $notAllowedHeaders));
        }

        foreach ($this->requiredHeaders as $header) {
            if (!\in_array($header, $this->headers)) {
                $requiredHeadersNotPresent[] = $header;
            }
        }

        if (!empty($requiredHeadersNotPresent)) {
            throw new HeaderSyntaxValidationException(
                'Required headers not present: ' . \implode(', ', $requiredHeadersNotPresent)
            );
        }
    }
}
