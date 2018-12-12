<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Agencies;
use App\Models\Gallery;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Admin;
use App\Models\Cities;
use App\Models\JobType;
use App\Models\JobLevel;
use App\Models\Salaries;
use App\Models\Degrees;
use App\Models\Majors;
use App\Models\JobFunction;
use App\Models\Industries;
use App\Models\Vacancies;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('id_ID');

        for ($c = 0; $c < 50; $c++) {
            $agency = Agencies::create([
                'company' => $faker->company,
                'kantor_pusat' => $faker->city,
                'industry_id' => rand(Industries::min('id'), Industries::max('id')),
                'tentang' => '<p align="justify">' . $faker->text($maxNbChars = 700) . '</p>',
                'alasan' => '<p align="justify">' . $faker->text($maxNbChars = 800) . '</p>',
                'link' => 'https://www.' . preg_replace('/\s+/', '', strtolower($faker->company)) . '.com',
                'alamat' => $faker->address,
                'phone' => $faker->phoneNumber,
                'hari_kerja' => 'Monday - Saturday',
                'jam_kerja' => '08:00 - 17:00',
                'lat' => $faker->latitude(-8, -6),
                'long' => $faker->longitude(111, 113)
            ]);
            Gallery::create([
                'agency_id' => $agency->id,
                'image' => 'c1.jpg',
            ]);
            Gallery::create([
                'agency_id' => $agency->id,
                'image' => 'c2.jpg',
            ]);
            Gallery::create([
                'agency_id' => $agency->id,
                'image' => 'c3.jpg',
            ]);

            Vacancies::create([
                'judul' => Factory::create()->jobTitle,
                'city_id' => rand(Cities::min('id'), Cities::max('id')),
                'syarat' => '<ul><li>' . $faker->sentence($nbWords = 10, $variableNbWords = true) .
                    '</li></ul>',
                'tanggungjawab' => '<ul><li>' . $faker->sentence($nbWords = 10, $variableNbWords = true) .
                    '</li></ul>',
                'pengalaman' => $faker->randomDigitNotNull,
                'jobtype_id' => rand(JobType::min('id'), JobType::max('id')),
                'joblevel_id' => rand(JobLevel::min('id'), JobLevel::max('id')),
                'industry_id' => $agency->industry_id,
                'salary_id' => rand(Salaries::min('id'), Salaries::max('id')),
                'agency_id' => $agency->id,
                'degree_id' => rand(Degrees::min('id'), Degrees::max('id')),
                'major_id' => rand(Majors::min('id'), Majors::max('id')),
                'jobfunction_id' => rand(JobFunction::min('id'), JobFunction::max('id')),
                'recruitmentDate_start' => today(),
                'recruitmentDate_end' => today()->addMonth(),
                'interview_date' => today()->addMonth()->addWeek(),
            ]);
            Vacancies::create([
                'judul' => Factory::create()->jobTitle,
                'city_id' => rand(Cities::min('id'), Cities::max('id')),
                'syarat' => '<ul><li>' . $faker->sentence($nbWords = 10, $variableNbWords = true) .
                    '</li></ul>',
                'tanggungjawab' => '<ul><li>' . $faker->sentence($nbWords = 10, $variableNbWords = true) .
                    '</li></ul>',
                'pengalaman' => $faker->randomDigitNotNull,
                'jobtype_id' => rand(JobType::min('id'), JobType::max('id')),
                'joblevel_id' => rand(JobLevel::min('id'), JobLevel::max('id')),
                'industry_id' => $agency->industry_id,
                'salary_id' => rand(Salaries::min('id'), Salaries::max('id')),
                'agency_id' => $agency->id,
                'degree_id' => rand(Degrees::min('id'), Degrees::max('id')),
                'major_id' => rand(Majors::min('id'), Majors::max('id')),
                'jobfunction_id' => rand(JobFunction::min('id'), JobFunction::max('id')),
                'recruitmentDate_start' => today(),
                'recruitmentDate_end' => today()->addMonth(),
                'interview_date' => today()->addMonth()->addWeek(),
            ]);
        }

        for ($c = 0; $c < 50; $c++) {
            $user = User::create([
                'ava' => 'seeker.png',
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'remember_token' => str_random(10),
                'status' => true,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'zip_code' => $faker->postcode,
                'birthday' => $faker->dateTimeThisCentury->format('Y-m-d'),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'relationship' => rand(0, 1) ? 'single' : 'married',
                'nationality' => $faker->country,
                'website' => 'https://www.' . preg_replace('/\s+/', '',
                        strtolower($faker->firstName)) . '.com',
                'lowest_salary' => '1000000',
                'highest_salary' => '5000000',
                'summary' => $faker->text($maxNbChars = 500),
            ]);

            Education::create([
                'user_id' => $user->id,
                'school_name' => 'State University of ' . $faker->city,
                'degree_id' => rand(Degrees::min('id'), Degrees::max('id')),
                'major_id' => rand(Majors::min('id'), Majors::max('id')),
                'awards' => $faker->sentence(5, true),
                'nilai' => $faker->randomFloat(8, 3, 4),
                'start_period' => (today()->subYears(rand(1, 4)))->format('Y'),
                'end_period' => today()->format('Y'),
            ]);
            $exp = Experience::create([
                'user_id' => $user->id,
                'job_title' => $faker->title,
                'joblevel_id' => rand(JobLevel::min('id'), JobLevel::max('id')),
                'company' => $faker->company,
                'jobfunction_id' => rand(JobFunction::min('id'), JobFunction::max('id')),
                'industry_id' => rand(Industries::min('id'), Industries::max('id')),
                'city_id' => rand(Cities::min('id'), Cities::max('id')),
                'salary_id' => rand(Salaries::min('id'), Salaries::max('id')),
                'start_date' => today()->subYears(rand(1, 9)),
                'end_date' => rand(0, 1) ? null : today(),
                'jobtype_id' => rand(JobType::min('id'), JobType::max('id')),
                'report_to' => $faker->name,
                'job_desc' => $faker->sentence(10, true)
            ]);
            $user->update([
                'total_exp' => Carbon::parse($exp->start_date)->diffInYears(Carbon::parse($exp->end_date))
            ]);
        }

        for ($c = 0; $c < 10; $c++) {
            Admin::create([
                'ava' => 'avatar.png',
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'role' => 'admin'
            ]);
        }

        User::find(1)->update([
            'email' => 'fiqy_a@icloud.com',
            'name' => 'Fiqy Ainuzzaqy'
        ]);

        Admin::find(1)->update([
            'email' => 'jquinn211215@gmail.com',
            'name' => 'jQuinn',
            'role' => 'root'
        ]);
    }
}
