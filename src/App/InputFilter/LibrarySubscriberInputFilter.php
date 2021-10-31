<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;

class LibrarySubscriberInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'        => 'first_name',
                'allow_empty' => false,
            ]
        )
             ->add(
                 [
                     'name'        => 'last_name',
                     'allow_empty' => false,
                 ]
             )
             ->add(
                 [
                     'name'        => 'email',
                     'allow_empty' => false,
                     'validators'  => [
                         ['name' => EmailAddress::class],
                     ],
                 ]
             );
    }
}
