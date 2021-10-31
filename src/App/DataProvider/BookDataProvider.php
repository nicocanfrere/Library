<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\Contract\AbstractDataProvider;
use Library\Contract\BookRepositoryInterface;
use Mezzio\Helper\UrlHelper;

use function array_merge;
use function count;

class BookDataProvider extends AbstractDataProvider
{
    public function __construct(
        protected BookRepositoryInterface $bookRepository,
        protected UrlHelper $urlHelper,
        protected string $class
    ) {
        parent::__construct($this->urlHelper, $this->class);
    }

    public function single(string $uuid): array
    {
        $book = $this->bookRepository->findBookByUuid($uuid) ?? [];
        if ($book) {
            $book = array_merge($this->createMeta('books.get', $book['uuid']), $book);
            if (! empty($book['subscriber'])) {
                $book['subscriber'] = [
                    $this->urlHelper->generate(
                        'subscribers.get',
                        ['uuid' => $book['subscriber'][0]['subscriber']]
                    ),
                ];
            }
        }

        return $book;
    }

    public function collection(): array
    {
        $books = $this->bookRepository->getLibraryCatalog();
        foreach ($books as $key => $book) {
            if (! empty($book['subscriber'])) {
                $book['subscriber'] = [
                    $this->urlHelper->generate(
                        'subscribers.get',
                        ['uuid' => $book['subscriber'][0]['subscriber']]
                    ),
                ];
            }
            $books[$key] = array_merge($this->createMeta('books.get', $book['uuid']), $book);
        }

        return [
            'count' => count($books),
            'items' => $books,
        ];
    }
}
