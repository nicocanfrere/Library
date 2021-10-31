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

class PatchHandler implements RequestHandlerInterface
{
    public function __construct(
        private InputFilterInterface $inputFilter,
        private UpdateBookInterface $updateBook,
        private DataProviderInterface $bookDataProvider
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
                //TODO log
                return new JsonResponse(['error' => ['message' => $exception->getMessage()]], 404);
            } catch (Exception $exception) {
                //TODO log
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
