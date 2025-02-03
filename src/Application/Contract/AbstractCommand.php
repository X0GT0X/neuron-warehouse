<?php

declare(strict_types=1);

namespace App\Application\Contract;

use Symfony\Component\Uid\Uuid;

readonly class AbstractCommand implements CommandInterface
{
    private Uuid $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
