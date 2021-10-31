<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Library\Contract\RemoveSubscriberInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteHandler implements RequestHandlerInterface
{
    public function __construct(
        private RemoveSubscriberInterface $removeSubscriber
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uuid = $request->getAttribute('uuid');
        try {
            $subscriber = $this->removeSubscriber->remove($uuid);

            return new JsonResponse($subscriber);
        } catch (LibrarySubscriberNotFoundException $exception) {
            //TODO log
            return new JsonResponse(['error' => ['message' => $exception->getMessage()]], 404);
        } catch (Exception $exception) {
            //TODO log
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}
