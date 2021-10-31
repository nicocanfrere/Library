<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Uuid;

class BookPatchInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'        => 'title',
                'allow_empty' => true,
            ]
        )
            ->add(
                [
                    'name'        => 'uuid',
                    'allow_empty' => false,
                    'validators'  => [
                        ['name' => Uuid::class],
                    ],
                ]
            )
             ->add(
                 [
                     'name'        => 'author_name',
                     'allow_empty' => true,
                 ]
             )
             ->add(
                 [
                     'name'        => 'year_of_publication',
                     'allow_empty' => true,
                     'validators'  => [
                         ['name' => IsInt::class],
                     ],
                 ]
             );
    }
}
