<?php

use App\Models\Core\Auth\User;
use App\Models\CRM\Pipeline\Pipeline;
use Illuminate\Database\Seeder;

class PipelinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pipeline::query()->insert([
            'name' => 'Pipeline One',
            'created_by' => array_rand(User::query()->pluck('id')->toArray())
        ]);
    }
}
