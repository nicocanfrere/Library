<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function file_get_contents;
use function json_decode;

class ApiDocHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $content = file_get_contents(__DIR__ . '/../../../data/api-doc/openapi.json');

        return new JsonResponse(json_decode($content));
    }
}
