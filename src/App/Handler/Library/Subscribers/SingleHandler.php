<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers;

use App\Contract\DataProviderInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SingleHandler implements RequestHandlerInterface
{
    public function __construct(private DataProviderInterface $provider)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uuid       = $request->getAttribute('uuid');
        $subscriber = $this->provider->single($uuid);

        return new JsonResponse($subscriber, $subscriber ? 200 : 404);
    }
}
