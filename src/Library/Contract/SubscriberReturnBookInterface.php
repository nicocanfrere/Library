<?php

declare(strict_types=1);

namespace Library\Contract;

interface SubscriberReturnBookInterface
{
    public function returnBook(string $subscriberUuid, string $bookUuid): array;
}
