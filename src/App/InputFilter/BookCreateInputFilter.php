<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;

class BookCreateInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'        => 'title',
                'allow_empty' => false,
            ]
        )
            ->add(
                [
                    'name'        => 'author_name',
                    'allow_empty' => false,
                ]
            )
            ->add(
                [
                    'name'        => 'year_of_publication',
                    'allow_empty' => false,
                    'validators'  => [
                        ['name' => IsInt::class],
                    ],
                ]
            );
    }
}
