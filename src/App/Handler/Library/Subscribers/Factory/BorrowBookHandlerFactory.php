<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\Handler\Library\Subscribers\BorrowBookHandler;
use Library\Contract\SubscriberBorrowBooksInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BorrowBookHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $subscriberBorrowBooks = $container->get(SubscriberBorrowBooksInterface::class);

        return new BorrowBookHandler($subscriberBorrowBooks);
    }
}
