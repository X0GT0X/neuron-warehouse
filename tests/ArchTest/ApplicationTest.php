<?php

declare(strict_types=1);

namespace App\Tests\ArchTest;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\AbstractCommand;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class ApplicationTest
{
    public function testThat_CommandHandlersClasses_AreFinal(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::implements(CommandHandlerInterface::class))
            ->shouldBeFinal();
    }

    public function testThat_Commands_ShouldExtendAbstractCommand(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname('/^App\Application\.+\.+\.+Command', true))
            ->shouldExtend()
            ->classes(Selector::classname(AbstractCommand::class));
    }
}
