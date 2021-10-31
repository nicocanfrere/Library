<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Library\Contract\SubscriberBorrowBooksInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class BorrowBookHandler implements RequestHandlerInterface
{
    public function __construct(
        private SubscriberBorrowBooksInterface $subscriberBorrowBooks,
        private LoggerInterface $logger
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data           = $request->getParsedBody();
        $subscriberUuid = $request->getAttribute('uuid');
        try {
            $result = $this->subscriberBorrowBooks->borrow($subscriberUuid, $data['books']);

            return new JsonResponse($result);
        } catch (LibrarySubscriberNotFoundException $exception) {
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
