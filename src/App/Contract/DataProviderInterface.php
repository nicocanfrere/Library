<?php

declare(strict_types=1);

namespace App\Contract;

interface DataProviderInterface
{
    public function single(string $uuid): array;

    public function collection(): array;

    public function createMeta(string $routeName = '', ?string $identifier = ''): array;
}
