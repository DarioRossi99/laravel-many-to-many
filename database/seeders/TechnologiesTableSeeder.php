<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TechnologiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $technologies = [

            "html",
            "css",
            "vue.js",
            "javascript",
            "php",
            "laravel"
        ];

        foreach($technologies as $technology) {

        $newTechnology = new Technology();
        $newTechnology->name = $technology;
        $newTechnology->save();
    }
    }
}