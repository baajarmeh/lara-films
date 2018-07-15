<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes_array = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];
        $values = [
            ['user_id' => 1, 'country' => 'united states', 'ticket_price' => 55, 'genre' => 'Sarah Vowell', 'name' => 'Incredibles 2', 'release_year' => 2018, 'rating' => 5, 'photo' => '', 'description' => 'Everyones favorite family of superheroes is back in "Incredibles 2"--but this time Helen (voice of Holly Hunter) is in the spotlight'],
            ['user_id' => 1, 'country' => 'united states', 'ticket_price' => 75, 'genre' => 'Huck Milner', 'name' => 'Incredibles 1', 'release_year' => 2018, 'rating' => 2, 'photo' => '', 'description' => 'Dash (voice of Huck Milner) to navigate the day-to-day heroics of "normal" life. Its a tough transistion for everyone, made tougher by the fact that the famil'],
            ['user_id' => 1, 'country' => 'united states', 'ticket_price' => 62, 'genre' => 'Samuel Jackson', 'name' => 'Incredibles 3', 'release_year' => 2018, 'rating' => 3, 'photo' => '', 'description' => 'villain hatches a brilliant and dangerous plot, the family and Frozone (voice of Samuel L. Jackson) must find a way to work together again--which is easier said than done, even when they'],
        ];

        foreach ($attributes_array as $attributes) {
            DB::table('films')->updateOrInsert($attributes, $values[$attributes['id']-1]);
        }
    }
}
