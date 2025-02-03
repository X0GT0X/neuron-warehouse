<?php

declare(strict_types=1);

namespace App\Tests\ArchTest;

use Neuron\BuildingBlocks\Domain\AbstractBusinessRule;
use Neuron\BuildingBlocks\Domain\AggregateRootInterface;
use Neuron\BuildingBlocks\Domain\DomainEventBase;
use Neuron\BuildingBlocks\Domain\Entity;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class DomainTest
{
    public function testThat_EntityDoesNotHaveReference_ToOtherAggregateRoot(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::extends(Entity::class))
            ->shouldNotDependOn()
            ->classes(Selector::implements(AggregateRootInterface::class));
    }

    public function testThat_DomainEvents_ShouldExtendDomainEventBase(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname('/^App\Domain\.+\Event\.+DomainEvent', true))
            ->shouldExtend()
            ->classes(Selector::classname(DomainEventBase::class));
    }

    public function testThat_BusinessRules_ShouldExtendAbstractBusinessRule(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname('/^App\Domain\.+\Rule\.+Rule', true))
            ->shouldExtend()
            ->classes(Selector::classname(AbstractBusinessRule::class));
    }
}
