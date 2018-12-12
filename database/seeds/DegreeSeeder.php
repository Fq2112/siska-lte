<?php

use Illuminate\Database\Seeder;
use App\Models\Degrees;

class DegreeSeeder extends Seeder
{
    const TINGKAT = [
        'Any',
        'High School',
        'Associate Degree',
        'Diploma Degree',
        'Bachelor\'s Degree',
        'Master\'s Degree',
        'Doctorate/Ph.D',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::TINGKAT as $tingkat) {
           Degrees::create([
               'name' => $tingkat
            ]);
        }
    }
}
