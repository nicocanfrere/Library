<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\AbstractRepository;
use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\LibrarySubscriber;

class LibrarySubscriberRepository extends AbstractRepository implements
    LibrarySubscriberRepositoryInterface
{
    public static array $metadata = [
        'class'   => LibrarySubscriber::class,
        'table'   => 'library_subscribers',
        'primary' => 'uuid',
        'columns' => [
            'uuid'       => [
                'property'   => 'uuid',
                'definition' => ['type' => 'uuid', 'options' => ['null' => false]],
            ],
            'first_name' => [
                'property'   => 'firstName',
                'definition' => ['type' => 'string', 'options' => ['limit' => 100, 'null' => true]],
            ],
            'last_name'  => [
                'property'   => 'lastName',
                'definition' => ['type' => 'string', 'options' => ['limit' => 100, 'null' => true]],
            ],
            'email'      => [
                'property'   => 'email',
                'definition' => ['type' => 'string', 'options' => ['limit' => 255, 'null' => true]],
            ],
        ],
    ];

    public function __construct(
        DatabaseConnectionInterface $connection,
        protected BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository
    ) {
        parent::__construct($connection);
    }

    public function registerNewLibrarySubscriber(LibrarySubscriberInterface $subscriber): void
    {
        $this->insert(self::$metadata, $subscriber);
    }

    public function unregisterLibrarySubscriber(LibrarySubscriberInterface $subscriber): void
    {
        $this->delete(self::$metadata, $subscriber);
    }

    public function listLibrarySubscribers(): array
    {
        $subscribers = $this->select(self::$metadata, ['last_name' => 'ASC']);
        foreach ($subscribers as $key => $subscriber) {
            $subscribers[$key] = $this->addBooksToSubscriber($subscriber);
        }

        return $subscribers;
    }

    public function findLibrarySubscriberByUuid(string $uuid): ?array
    {
        $conditions = [
            [
                'condition'  => 'uuid = :uuid',
                'parameters' => [
                    'uuid' => $uuid,
                ],
            ],
        ];
        $subscriber = $this->selectSingleWhere(self::$metadata, $conditions);
        if ($subscriber) {
            $subscriber = $this->addBooksToSubscriber($subscriber);
        }

        return $subscriber;
    }

    public function addBooksToSubscriber(array $subscriber): array
    {
        $conditions          = [
            [
                'condition'  => 'subscriber = :subscriber_uuid',
                'parameters' => [
                    'subscriber_uuid' => $subscriber['uuid'],
                ],
            ],
        ];
        $subscriber['books'] = $this->bookBorrowRegistryRepository
            ->selectWhere(BookBorrowRegistryRepository::$metadata, $conditions);

        return $subscriber;
    }

    public function findOneByEmail(string $email): ?array
    {
        $conditions = [
            [
                'condition'  => 'email = :email',
                'parameters' => [
                    'email' => $email,
                ],
            ],
        ];
        $subscriber = $this->selectSingleWhere(self::$metadata, $conditions);
        if ($subscriber) {
            $subscriber = $this->addBooksToSubscriber($subscriber);
        }

        return $subscriber;
    }

    public function updateSubscriber(LibrarySubscriberInterface $subscriber): void
    {
        $this->update(self::$metadata, $subscriber);
    }
}
