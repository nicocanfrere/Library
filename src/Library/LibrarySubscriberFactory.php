<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Exception\LibrarySubscriberEmailAlreadyUsedException;
use Ramsey\Uuid\Uuid;

class LibrarySubscriberFactory implements LibrarySubscriberFactoryInterface
{
    public function __construct(private LibrarySubscriberRepositoryInterface $repository)
    {
    }

    public function create(array $data, ?bool $save = true): LibrarySubscriberInterface
    {
        if ($save) {
            $exists = $this->repository->findOneByEmail($data['email']);
            if (! empty($exists)) {
                throw new LibrarySubscriberEmailAlreadyUsedException();
            }
        }
        $uuid       = ! empty($data['uuid']) ? $data['uuid'] : Uuid::uuid4()->toString();
        $subscriber = LibrarySubscriber::create(
            $uuid,
            $data['first_name'],
            $data['last_name'],
            $data['email']
        );
        if (empty($data['uuid']) && $save) {
            $this->repository->registerNewLibrarySubscriber($subscriber);
        }

        return $subscriber;
    }
}
