<?php

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ApiDocHandler
 */
class ApiDocHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $content = file_get_contents(__DIR__ . '/../../../data/api-doc/openapi.json');

        return new JsonResponse(\json_decode($content));
    }
}
