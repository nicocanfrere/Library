<?php

declare(strict_types=1);

namespace Library\Exception;

use Exception;

class LibrarySubscriberEmailAlreadyUsedException extends Exception
{
    protected $message = 'library_subscriber.email_already_used.error';
    protected $code    = 422;
}
