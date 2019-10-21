<?php

namespace FlexPHP\Generator\Domain\Builders;

interface BuilderInterface
{
    public function getFileTemplate(): string;

    public function getPathTemplate(): string;

    public function build(): string;
}
