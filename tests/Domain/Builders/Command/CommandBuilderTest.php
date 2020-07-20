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
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Constants\Keyword;
use FlexPHP\Schema\Schema;

final class CommandBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new CommandBuilder('Test', 'action', Schema::fromArray([
            'EntityBar' => [
                Keyword::TITLE => 'Entity Bar Title',
                Keyword::ATTRIBUTES => [
                    [
                        Keyword::NAME => 'foo',
                        Keyword::DATATYPE => 'integer',
                    ],
                    [
                        Keyword::NAME => 'Bar',
                        Keyword::DATATYPE => 'string',
                    ],
                ],
            ],
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Command\Test;

use Domain\Test\Request\ActionTestRequest;
use Domain\Test\UseCase\ActionTestUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

final class ActionTestCommand extends Command
{
    protected function configure()
    {
        \$this
            ->setName('tests:action')
            ->setDescription('Command to Action on Test')
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

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItDiffEntityName(string $entity, string $expectedCamel, string $expectedDash): void
    {
        $render = new CommandBuilder($entity, 'action');

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Command\\{$expectedCamel};

use Domain\\{$expectedCamel}\\Request\Action{$expectedCamel}Request;
use Domain\\{$expectedCamel}\\UseCase\Action{$expectedCamel}UseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

final class Action{$expectedCamel}Command extends Command
{
    protected function configure()
    {
        \$this
            ->setName('{$expectedDash}:action')
            ->setDescription('Command to Action on {$expectedCamel}');
    }

    protected function execute(InputInterface \$input, OutputInterface \$output)
    {
        \$data = \$input->getArguments();

        \$request = new Action{$expectedCamel}Request(\$data);
        \$useCase = new Action{$expectedCamel}UseCase();
        \$response = \$useCase->execute(\$request);

        \$output->writeln('Action on {$expectedCamel} done!');
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getActionName
     */
    public function testItDiffActionName(string $action): void
    {
        $render = new CommandBuilder('test', $action);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Command\Test;

use Domain\Test\Request\CustomActionTestRequest;
use Domain\Test\UseCase\CustomActionTestUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

final class CustomActionTestCommand extends Command
{
    protected function configure()
    {
        \$this
            ->setName('tests:custom-action')
            ->setDescription('Command to CustomAction on Test');
    }

    protected function execute(InputInterface \$input, OutputInterface \$output)
    {
        \$data = \$input->getArguments();

        \$request = new CustomActionTestRequest(\$data);
        \$useCase = new CustomActionTestUseCase();
        \$response = \$useCase->execute(\$request);

        \$output->writeln('CustomAction on Test done!');
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword', 'userpasswords'],
            ['USERPASSWORD', 'Userpassword', 'userpasswords'],
            ['UserPassword', 'UserPassword', 'user-passwords'],
            ['userPassword', 'UserPassword', 'user-passwords'],
            ['user_password', 'UserPassword', 'user-passwords'],
            ['user-password', 'UserPassword', 'user-passwords'],
            ['Posts', 'Post', 'posts'],
        ];
    }

    public function getActionName(): array
    {
        return [
            ['custom_action'],
            ['custom action'],
            ['Custom Action'],
            ['cUSTOM aCtion'],
            ['customAction'],
            ['CustomAction'],
            ['custom-action'],
        ];
    }
}
