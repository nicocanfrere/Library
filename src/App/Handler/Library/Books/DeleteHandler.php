<?php

declare(strict_types=1);

namespace App\Handler\Library\Books;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Library\Contract\RemoveBookInterface;
use Library\Exception\BookNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class DeleteHandler implements RequestHandlerInterface
{
    public function __construct(
        private RemoveBookInterface $removeBook,
        private LoggerInterface $logger
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uuid = $request->getAttribute('uuid');
        try {
            $book = $this->removeBook->remove($uuid);

            return new JsonResponse($book);
        } catch (BookNotFoundException $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            return new JsonResponse(['error' => ['message' => $exception->getMessage()]], 404);
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            return new JsonResponse([], 500);
        }
    }
}
