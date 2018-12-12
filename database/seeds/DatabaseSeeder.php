<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            DegreeSeeder::class,
            NationSeeder::class,
            ProvinceSeeder::class,
            CitiesSeeder::class,
            JobFunctionSeeder::class,
            IndustriesSeeder::class,
            MajorSeeder::class,
            JobLevelSeeder::class,
            JobTypeSeeder::class,
            SalarySeeder::class,
            UserSeeder::class,
        ]);
    }
}
