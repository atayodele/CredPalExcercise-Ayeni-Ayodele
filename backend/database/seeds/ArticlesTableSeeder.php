<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {$faker = \Faker\Factory::create();

        for($i=0; $i<=5; $i++):
            DB::table('authors')
                ->insert([
                    'name' => $faker->name,
                    'surname' => $faker->lastName." ".$faker->firstName
                ]);
        endfor;
    }
}
