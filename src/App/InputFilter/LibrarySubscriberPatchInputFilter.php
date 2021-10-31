<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;

class LibrarySubscriberPatchInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'        => 'first_name',
                'allow_empty' => true,
            ]
        )
             ->add(
                 [
                     'name'        => 'last_name',
                     'allow_empty' => true,
                 ]
             )
             ->add(
                 [
                     'name'        => 'email',
                     'allow_empty' => true,
                     'validators'  => [
                         ['name' => EmailAddress::class],
                     ],
                 ]
             );
    }
}
