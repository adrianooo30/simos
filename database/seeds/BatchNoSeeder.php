<?php

use Illuminate\Database\Seeder;

class BatchNoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\BatchNo::class, 21)->create();
    }
}
