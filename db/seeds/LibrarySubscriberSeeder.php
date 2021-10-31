<?php

declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class LibrarySubscriberSeeder extends AbstractSeed
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
        $faker       = Factory::create('fr_Fr');
        $subscribers = [];
        for ($i = 0; $i < 20; $i++) {
            $subscribers[] = [
                'uuid'       => Uuid::uuid4()->toString(),
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->safeEmail,
            ];
        }

        $tbLibrarySubscribers = $this->table('library_subscribers');
        $tbLibrarySubscribers->insert($subscribers)
                ->saveData();
    }
}
