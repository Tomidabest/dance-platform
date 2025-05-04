<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Studio;
use App\Models\Instructor;
use App\Models\Classes;
use App\Models\Bookings;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory(10)->create();

        Studio::factory(5)->create()->each(function ($studio) {
            $studio->instructors()->saveMany(Instructor::factory(2)->make());
        });

        Classes::factory(15)->create();

        Bookings::factory(20)->create();
    }
}
