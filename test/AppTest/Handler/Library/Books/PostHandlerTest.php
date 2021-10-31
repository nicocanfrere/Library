<?php

declare(strict_types=1);

namespace AppTest\Handler\Library\Books;

use App\Contract\DataProviderInterface;
use App\Handler\Library\Books\PostHandler;
use Laminas\InputFilter\InputFilterInterface;
use Library\BookFactory;
use Library\Contract\BookRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PostHandlerTest extends TestCase
{
    protected InputFilterInterface $inputFilter;
    protected DataProviderInterface $bookDataProvider;
    protected BookRepositoryInterface $bookRepository;

    protected function setUp(): void
    {
        $this->inputFilter      = $this->createMock(InputFilterInterface::class);
        $this->bookDataProvider = $this->createMock(DataProviderInterface::class);
        $this->bookRepository   = $this->createMock(BookRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function handleNoError()
    {
        $values  = ['title' => 'title', 'author_name' => 'author_name', 'year_of_publication' => 2021];
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn($values);
        $this->inputFilter->method('isValid')->willReturn(true);
        $this->inputFilter->method('getValues')->willReturn($values);

        $handler = new PostHandler(
            $this->inputFilter,
            new BookFactory($this->bookRepository),
            $this->bookDataProvider
        );

        $response = $handler->handle($request);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
