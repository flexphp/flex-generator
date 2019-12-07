<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Command;

use FlexPHP\Generator\Domain\Builders\Command\CommandBuilder;
use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Tests\TestCase;

class CommandBuilderTest extends TestCase
{
    public function testItRenderOk()
    {
        $action = 'action';
        $entity = 'Test';
        $properties = [
            'foo' => [
                Keyword::NAME => 'Foo',
                Keyword::DATA_TYPE => 'integer',
            ],
            'bar' => [
                Keyword::NAME => 'Bar',
                Keyword::DATA_TYPE => 'varchar',
            ],
        ];

        $render = new CommandBuilder([
            'entity' => $entity,
            'action' => $action,
            'properties' => $properties,
        ]);

        $this->assertEquals(str_replace("\r\n","\n", <<<T
<?php

namespace Domain\Test\Command;

use Domain\Test\Message\ActionTestRequest;
use Domain\Test\UseCase\ActionTestUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

/**
 * Command to action on Test.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class ActionTestCommand extends Command
{
    protected function configure(\$request): ActionTestResponse
    {
        \$this
            ->setName('test:action')
            ->setDescription('Command to action on Test')
            ->addArgument('foo', InputArgument::REQUIRED)
            ->addArgument('bar', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface \$input, OutputInterface \$output)
    {
        \$foo = \$input->getArgument('foo');
        \$bar = \$input->getArgument('bar');

        \$request = new ActionTestRequest(\$foo, \$bar);
        \$useCase = new ActionTestUseCase();
        \$response = \$useCase->execute(\$request);

        \$output->writeln('Action on Test done!'); 
    }
}

T), $render->build());
    }
}
