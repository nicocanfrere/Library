<?php

declare(strict_types=1);

namespace App\Handler\Library\Books;

use App\Contract\DataProviderInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Library\Contract\BookFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class PostHandler implements RequestHandlerInterface
{
    public function __construct(
        private InputFilterInterface $inputFilter,
        private BookFactoryInterface $bookFactory,
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
                $book     = $this->bookFactory->create($filtered);
                $book     = $this->bookDataProvider->single($book->getUuid());

                return new JsonResponse($book, 201);
            } catch (Exception $exception) {
                $this->logger->critical(
                    __METHOD__,
                    ['error' => $exception->getMessage()]
                );
                return new JsonResponse([], 500);
            }
        }

        $errors = [];
        foreach ($this->inputFilter->getInvalidInput() as $error) {
            $errors[$error->getName()] = $error->getMessages();
        }

        return new JsonResponse(['errors' => $errors], 422);
    }
}
