<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;

class DefaultPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Plan::create([
            'name' => 'Default',
            'price' => 0,
            'repository_limit' => 0,
            'visible' => 0,
        ]);
    }
}
