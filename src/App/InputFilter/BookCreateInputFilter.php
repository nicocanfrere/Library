<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

class BookCreateInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'        => 'title',
                'allow_empty' => false,
                'validators'  => [
                    ['name' => StringLength::class, 'options' => ['max' => 255]],
                ],
            ]
        )
            ->add(
                [
                    'name'        => 'author_name',
                    'allow_empty' => false,
                    'validators'  => [
                        ['name' => StringLength::class, 'options' => ['max' => 50]],
                    ],
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
