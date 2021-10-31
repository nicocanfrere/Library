<?php

declare(strict_types=1);

use Infrastructure\Library\Repository\BookBorrowRegistryRepository;
use Phinx\Migration\AbstractMigration;

final class BookBorrowRegistryCreate extends AbstractMigration
{
    public function up(): void
    {
        $metadata           = BookBorrowRegistryRepository::$metadata;
        $primary            = $metadata['primary'];
        $bookBorrowRegistry = $this->table(
            $metadata['table'],
            [
                'id'          => false,
                'primary_key' => [$primary],
            ]
        );
        foreach ($metadata['columns'] as $name => $column) {
            $bookBorrowRegistry->addColumn(
                $name,
                $column['definition']['type'],
                $column['definition']['options'],
            );
            if (! empty($column['indexes'])) {
                foreach ($column['indexes'] as $index) {
                    $bookBorrowRegistry->addIndex(
                        $index['columns'],
                        $index['options'],
                    );
                }
            }
        }
        if (array_key_exists('foreign_keys', $metadata)) {
            foreach ($metadata['foreign_keys'] as $foreignKey) {
                $bookBorrowRegistry->addForeignKey(
                    $foreignKey['column'],
                    $foreignKey['table'],
                    $foreignKey['ref'],
                    $foreignKey['options']
                );
            }
        }
        $bookBorrowRegistry->save();
    }

    public function down(): void
    {
        $metadata = BookBorrowRegistryRepository::$metadata;
        $this->table($metadata['table'])->drop()->save();
    }
}
