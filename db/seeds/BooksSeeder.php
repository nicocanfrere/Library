<?php

declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class BooksSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $faker = Factory::create('fr_Fr');
        $books = [];
        for ($i = 0; $i < 10; $i++) {
            $books[] = [
                'uuid'                => Uuid::uuid4()->toString(),
                'title'               => $faker->words(5, true),
                'author_name'         => $faker->name,
                'year_of_publication' => $faker->year,
            ];
        }
        $tbBooks = $this->table('books');
        $tbBooks->insert($books)
              ->saveData();
    }
}
