<?php

declare(strict_types=1);

namespace Library;

use JsonSerializable;
use Library\Contract\LibrarySubscriberInterface;

class LibrarySubscriber implements LibrarySubscriberInterface, JsonSerializable
{
    private ?string $uuid      = null;
    private ?string $firstName = null;
    private ?string $lastName  = null;
    private ?string $email     = null;

    public function __construct()
    {
    }

    public static function create(
        string $uuid,
        string $firstName,
        string $lastName,
        string $email
    ): LibrarySubscriberInterface {
        $subscriber            = new static();
        $subscriber->uuid      = $uuid;
        $subscriber->firstName = $firstName;
        $subscriber->lastName  = $lastName;
        $subscriber->email     = $email;

        return $subscriber;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid'       => $this->uuid,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
        ];
    }
}
