<?php

declare(strict_types=1);

namespace Library\Contract;

interface LibrarySubscriberRepositoryInterface
{
    public function registerNewLibrarySubscriber(LibrarySubscriberInterface $subscriber): void;

    public function unregisterLibrarySubscriber(LibrarySubscriberInterface $subscriber): void;

    public function updateSubscriber(LibrarySubscriberInterface $subscriber): void;

    public function listLibrarySubscribers(): array;

    public function findLibrarySubscriberByUuid(string $uuid): ?array;

    public function findOneByEmail(string $email): ?array;
}
