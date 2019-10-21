<?php

namespace FlexPHP\Generator\Domain\Builders;

class RequestMessageBuilder extends AbstractBuilder
{
    public function __construct(array $data, array $config = [])
    {
        if (!empty($data['action'])) {
            $data['action_name'] = $this->camelCase($data['action']);
        }

        parent::__construct($data, $config);
    }

    public function getFileTemplate(): string
    {
        return 'Request.php.twig';
    }

    public function getPathTemplate(): string
    {
        return \sprintf('%1$s/FlexPHP/Message', parent::getPathTemplate());
    }

    public function build(): string
    {
        return rtrim(parent::build());
    }

    public function camelCase(string $string): string
    {
        return str_replace(' ', '', \ucwords(\str_replace('_', ' ', $string)));
    }
}
