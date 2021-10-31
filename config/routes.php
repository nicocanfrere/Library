<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {

    /* Library Subscribers Management */
    $app->get('/api/library/subscribers', App\Handler\Library\Subscribers\ListHandler::class, 'subscribers.list');
    $app->get(
        '/api/library/subscribers/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}',
        [
            App\Handler\Library\Subscribers\SingleHandler::class
        ],
        'subscribers.get'
    );
    $app->post('/api/library/subscribers', App\Handler\Library\Subscribers\PostHandler::class, 'subscribers.create');
    $app->patch('/api/library/subscribers/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Subscribers\PatchHandler::class, 'subscribers.patch');
    $app->put('/api/library/subscribers/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Subscribers\PutHandler::class, 'subscribers.put');
    $app->delete('/api/library/subscribers/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Subscribers\DeleteHandler::class, 'subscribers.delete');

    $app->post(
        '/api/library/subscribers/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}/books',
        App\Handler\Library\Subscribers\BorrowBookHandler::class,
        'subscribers.borrow_books'
    );
    $app->delete(
        '/api/library/subscribers/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}/books/{bookUuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}',
        App\Handler\Library\Subscribers\BringBackBookHandler::class,
        'subscribers.bring_back_book'
    );



    /* Library Books Management */
    $app->get('/api/library/books', App\Handler\Library\Books\ListHandler::class, 'books.list');
    $app->get('/api/library/books/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Books\SingleHandler::class, 'books.get');
    $app->post('/api/library/books', App\Handler\Library\Books\PostHandler::class, 'books.create');
    $app->patch('/api/library/books/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Books\PatchHandler::class, 'books.patch');
    $app->put('/api/library/books/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Books\PutHandler::class, 'books.put');
    $app->delete('/api/library/books/{uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', App\Handler\Library\Books\DeleteHandler::class, 'books.delete');
};
