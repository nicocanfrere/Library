<?php

declare(strict_types=1);

namespace Library\Exception;

use Exception;

class LibrarySubscriberNotFoundException extends Exception
{
    /** @var int */
    protected $code = 404;
    /** @var string */
    protected $message = 'library_subscriber.not_found.error';
}
