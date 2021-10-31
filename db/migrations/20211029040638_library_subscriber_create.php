<?php

declare(strict_types=1);

use Infrastructure\Library\Repository\LibrarySubscriberRepository;
use Phinx\Migration\AbstractMigration;

final class LibrarySubscriberCreate extends AbstractMigration
{
    public function up(): void
    {
        $metadata    = LibrarySubscriberRepository::$metadata;
        $primary     = $metadata['primary'];
        $subscribers = $this->table(
            $metadata['table'],
            [
                'id'          => false,
                'primary_key' => [$primary],
            ]
        );
        foreach ($metadata['columns'] as $name => $column) {
            $subscribers->addColumn(
                $name,
                $column['definition']['type'],
                $column['definition']['options'],
            );
        }
        $subscribers->save();
    }

    public function down(): void
    {
        $metadata = LibrarySubscriberRepository::$metadata;
        $this->table($metadata['table'])->drop()->save();
    }
}
