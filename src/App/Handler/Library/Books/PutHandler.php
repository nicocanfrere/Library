<?php

declare(strict_types=1);

namespace App\Handler\Library\Books;

use App\Contract\DataProviderInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Library\Contract\UpdateBookInterface;
use Library\Exception\BookNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class PutHandler implements RequestHandlerInterface
{
    public function __construct(
        private InputFilterInterface $inputFilter,
        private UpdateBookInterface $updateBook,
        private DataProviderInterface $bookDataProvider,
        private LoggerInterface $logger
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var array $data */
        $data = $request->getParsedBody();
        $this->inputFilter->setData($data);
        if ($this->inputFilter->isValid()) {
            try {
                $filtered = $this->inputFilter->getValues();
                $book     = $this->updateBook->update($request->getAttribute('uuid'), $filtered);
                $book     = $this->bookDataProvider->single($book->getUuid());

                return new JsonResponse($book, 200);
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

        return (new JsonResponse(['error' => 'invalid data']))->withStatus(422);
    }
}
