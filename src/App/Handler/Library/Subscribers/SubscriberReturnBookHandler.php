<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Library\Contract\SubscriberReturnBookInterface;
use Library\Exception\BookNotBorrowedBySubscriberException;
use Library\Exception\BookNotFoundInRegistryException;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class SubscriberReturnBookHandler implements RequestHandlerInterface
{
    public function __construct(
        private SubscriberReturnBookInterface $subscriberReturnBook,
        private LoggerInterface $logger
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $result = $this->subscriberReturnBook->returnBook(
                $request->getAttribute('uuid'),
                $request->getAttribute('bookUuid')
            );
            return new JsonResponse($result);
        } catch (
            LibrarySubscriberNotFoundException |
            BookNotFoundInRegistryException |
            BookNotBorrowedBySubscriberException $exception
        ) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            return new JsonResponse(['error' => $exception->getMessage()], 404);
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}
