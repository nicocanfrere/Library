<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\Contract\AbstractDataProvider;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Mezzio\Helper\UrlHelper;

use function array_merge;
use function count;

class LibrarySubscriberDataProvider extends AbstractDataProvider
{
    public function __construct(
        protected LibrarySubscriberRepositoryInterface $librarySubscriberRepository,
        protected UrlHelper $urlHelper,
        protected string $class
    ) {
    }

    public function single(string $uuid): array
    {
        $subscriber = $this->librarySubscriberRepository->findLibrarySubscriberByUuid($uuid) ?? [];
        if ($subscriber) {
            $subscriber = array_merge($this->createMeta('subscribers.get', $subscriber['uuid']), $subscriber);
            if (! empty($subscriber['books'])) {
                foreach ($subscriber['books'] as $key => $data) {
                    $subscriber['books'][$key] = $data['book'] ? $this->urlHelper->generate(
                        'books.get',
                        ['uuid' => $data['book']]
                    ) : null;
                }
            }
        }

        return $subscriber;
    }

    public function collection(): array
    {
        $subscribers = $this->librarySubscriberRepository->listLibrarySubscribers();
        foreach ($subscribers as $key => $subscriber) {
            if (! empty($subscriber['books'])) {
                foreach ($subscriber['books'] as $k => $data) {
                    $subscriber['books'][$k] = $data['book'] ? $this->urlHelper->generate(
                        'books.get',
                        ['uuid' => $data['book']]
                    ) : null;
                }
            }
            $subscribers[$key] = array_merge($this->createMeta('subscribers.get', $subscriber['uuid']), $subscriber);
        }

        return [
            'count' => count($subscribers),
            'items' => $subscribers,
        ];
    }
}
