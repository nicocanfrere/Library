<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers;

use App\Contract\DataProviderInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Library\Contract\UpdateSubscriberInterface;
use Library\Exception\LibrarySubscriberEmailAlreadyUsedException;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PatchHandler implements RequestHandlerInterface
{
    public function __construct(
        private InputFilterInterface $inputFilter,
        private UpdateSubscriberInterface $updateSubscriber,
        private DataProviderInterface $provider
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var array $data */
        $data = $request->getParsedBody();
        $this->inputFilter->setData($data);
        if ($this->inputFilter->isValid()) {
            try {
                $filtered   = $this->inputFilter->getValues();
                $subscriber = $this->updateSubscriber->update($request->getAttribute('uuid'), $filtered);
                $subscriber = $this->provider->single($subscriber->getUuid());

                return new JsonResponse($subscriber, 200);
            } catch (LibrarySubscriberNotFoundException $exception) {
                //TODO log
                return new JsonResponse(['error' => ['message' => $exception->getMessage()]], 404);
            } catch (LibrarySubscriberEmailAlreadyUsedException $exception) {
                //TODO log
                return new JsonResponse(['error' => ['message' => $exception->getMessage()]], 422);
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
