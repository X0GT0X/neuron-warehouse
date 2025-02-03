<?php

declare(strict_types=1);

namespace App\Tests\ArchTest;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class LayersTest
{
    public function testThat_DomainLayer_DoesNotHaveDependency_ToOtherLayers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Application'),
                Selector::inNamespace('App\Infrastructure'),
                Selector::inNamespace('App\UserInterface'),
            );
    }

    public function testThat_ApplicationLayer_DoesNotHaveDependency_ToInfrastructureAndUILayer(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Infrastructure'),
                Selector::inNamespace('App\UserInterface'),
            );
    }

    public function testThat_InfrastructureLayer_DoesNotHaveDependency_ToUILayer(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Infrastructure'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\UserInterface'),
            );
    }
}
