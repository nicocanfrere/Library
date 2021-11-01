<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Infrastructure\Contract\ResourceMetadataInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\LibrarySubscriber;

class LibrarySubscriberRepository implements
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
        private QueryInterface $query,
        private ResourceMetadataInterface $resourceMetadata,
        private BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository
    ) {
    }

    public function registerNewLibrarySubscriber(LibrarySubscriberInterface $subscriber): void
    {
        $this->query->insert($this->resourceMetadata, $subscriber);
    }

    public function unregisterLibrarySubscriber(LibrarySubscriberInterface $subscriber): void
    {
        $this->query->delete($this->resourceMetadata, $subscriber);
    }

    public function listLibrarySubscribers(): array
    {
        $subscribers = $this->query->select($this->resourceMetadata, ['last_name' => 'ASC']);
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
        $subscriber = $this->query->selectSingleWhere($this->resourceMetadata, $conditions);
        if ($subscriber) {
            $subscriber = $this->addBooksToSubscriber($subscriber);
        }

        return $subscriber;
    }

    public function addBooksToSubscriber(array $subscriber): array
    {
        $subscriber['books'] = $this->bookBorrowRegistryRepository
            ->findAllBySubscriberUuid($subscriber['uuid']);

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
        $subscriber = $this->query->selectSingleWhere($this->resourceMetadata, $conditions);
        if ($subscriber) {
            $subscriber = $this->addBooksToSubscriber($subscriber);
        }

        return $subscriber;
    }

    public function updateSubscriber(LibrarySubscriberInterface $subscriber): void
    {
        $this->query->update($this->resourceMetadata, $subscriber);
    }
}
