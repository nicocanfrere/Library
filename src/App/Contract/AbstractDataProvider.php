<?php

declare(strict_types=1);

namespace App\Contract;

use Mezzio\Helper\UrlHelper;

use function strrpos;
use function substr;

abstract class AbstractDataProvider implements DataProviderInterface
{
    public function __construct(
        protected UrlHelper $urlHelper,
        protected string $class
    ) {
    }

    public function createMeta(string $routeName = '', ?string $identifier = ''): array
    {
        $id = $identifier ?
            $this->urlHelper->generate($routeName, ['uuid' => $identifier])
            : $this->urlHelper->generate($routeName);

        return [
            '@id'   => $id,
            '@type' => substr($this->class, strrpos($this->class, '\\') + 1),
        ];
    }

    abstract public function single(string $uuid): array;

    abstract public function collection(): array;
}
