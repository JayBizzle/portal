<?php

namespace App\Seeds;

use App\Forum\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run()
    {
        factory(Topic::class)->create(['name' => 'Laravel']);
        factory(Topic::class)->create(['name' => 'Lumen']);
        factory(Topic::class)->create(['name' => 'Spark']);
    }
}