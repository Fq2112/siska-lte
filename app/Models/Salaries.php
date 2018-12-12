<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salaries extends Model
{
    protected $table = 'salaries';

    protected $guarded = ['id'];

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'salary_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'salary_id');
    }
}
