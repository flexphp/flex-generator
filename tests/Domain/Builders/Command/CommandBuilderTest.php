<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Command;

use FlexPHP\Generator\Domain\Builders\Command\CommandBuilder;
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Generator\Tests\TestCase;

final class CommandBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $action = 'action';
        $entity = 'Test';
        $properties = [
            [
                Keyword::NAME => 'foo',
                Keyword::DATATYPE => 'integer',
            ],
            [
                Keyword::NAME => 'Bar',
                Keyword::DATATYPE => 'varchar',
            ],
        ];

        $render = new CommandBuilder($entity, $action, $properties);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Command;

use Domain\Test\Message\ActionTestRequest;
use Domain\Test\UseCase\ActionTestUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

final class ActionTestCommand extends Command
{
    protected function configure(\$request): ActionTestResponse
    {
        \$this
            ->setName('test:action')
            ->setDescription('Command to action on Test')
            ->addArgument('foo', InputArgument::REQUIRED)
            ->addArgument('Bar', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface \$input, OutputInterface \$output)
    {
        \$data = \$input->getArguments();

        \$request = new ActionTestRequest(\$data);
        \$useCase = new ActionTestUseCase();
        \$response = \$useCase->execute(\$request);

        \$output->writeln('Action on Test done!'); 
    }
}

T, $render->build());
    }
}
