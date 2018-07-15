<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
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
            ['user_id' => 1, 'film_id' => 1, 'name' => 'Milner', 'comment' => 'made tougher by the fact that the family is still unaware of baby Jack-Jacks emerging superpowers'],
            ['user_id' => 1, 'film_id' => 2, 'name' => 'Samuel', 'comment' => 'villain hatches a brilliant and dangerous plot, the family and Frozone'],
            ['user_id' => 1, 'film_id' => 3, 'name' => 'Huck', 'comment' => 'and Dash (voice of Huck Milner) to navigate the day-to-day heroic'],
        ];

        foreach ($attributes_array as $attributes) {
            DB::table('comments')->updateOrInsert($attributes, $values[$attributes['id']-1]);
        }
    }
}
