<?php

declare(strict_types=1);

use Infrastructure\Library\Repository\BookRepository;
use Phinx\Migration\AbstractMigration;

final class BookCreate extends AbstractMigration
{
    public function up(): void
    {
        $metadata = BookRepository::$metadata;
        $primary  = $metadata['primary'];
        $books    = $this->table(
            $metadata['table'],
            [
                'id'          => false,
                'primary_key' => [$primary],
            ]
        );
        foreach ($metadata['columns'] as $name => $column) {
            $books->addColumn(
                $name,
                $column['definition']['type'],
                $column['definition']['options'],
            );
        }
        $books->save();
    }

    public function down(): void
    {
        $metadata = BookRepository::$metadata;
        $this->table($metadata['table'])->drop()->save();
    }
}
