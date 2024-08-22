<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'Event 1',
            'description' => 'Description of event 1',
            'datetime' => '2024-07-24 13:15:41',
            'latitude' => 45.464664,
            'longitude' => 9.188540,
            'price' => 10.00,
            'image_path' => 'image1.jpg',
            'additional_info' => 'Additional info for event 1',
            'created_by' => 1,
        ]);

        DB::table('events')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'Event 2',
            'description' => 'Description of event 2',
            'datetime' => '2024-07-25 13:15:41',
            'latitude' => 42.464664,
            'longitude' => 9.188540,
            'price' => 20.00,
            'image_path' => 'image2.jpg',
            'additional_info' => 'Additional info for event 2',
            'created_by' => 1,
        ]);

        DB::table('events')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'Event 3',
            'description' => 'Description of event 3',
            'datetime' => '2024-07-26 13:15:41',
            'latitude' => 46.464664,
            'longitude' => 9.188540,
            'price' => 30.00,
            'image_path' => 'image3.jpg',
            'additional_info' => 'Additional info for event 3',
            'created_by' => 1,
        ]);

        DB::table('events')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'Event 4',
            'description' => 'Description of event 4',
            'datetime' => '2024-07-27 13:15:41',
            'latitude' => 48.464664,
            'longitude' => 11.188540,
            'price' => 40.00,
            'image_path' => 'image4.jpg',
            'additional_info' => 'Additional info for event 4',
            'created_by' => 1,
        ]);

        DB::table('events')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'Event 5',
            'description' => 'Description of event 5',
            'datetime' => '2024-07-28 13:15:41',
            'latitude' => 47.534664,
            'longitude' => 10.188540,
            'price' => 50.00,
            'image_path' => 'image5.jpg',
            'additional_info' => 'Additional info for event 5',
            'created_by' => 1,
        ]);
    }
}
