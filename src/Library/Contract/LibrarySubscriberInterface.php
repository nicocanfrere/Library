<?php

declare(strict_types=1);

namespace Library\Contract;

interface LibrarySubscriberInterface
{
    public function __construct();

    public static function create(
        string $uuid,
        string $firstName,
        string $lastName,
        string $email
    ): LibrarySubscriberInterface;

    public function getUuid(): ?string;

    public function getLastName(): ?string;

    public function getFirstName(): ?string;

    public function getEmail(): ?string;
}
