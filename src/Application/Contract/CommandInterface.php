<?php

declare(strict_types=1);

namespace App\Application\Contract;

use Symfony\Component\Uid\Uuid;

interface CommandInterface
{
    public function getId(): Uuid;
}
