<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobFunction extends Model
{
    protected $table = 'job_functions';

    protected $guarded = ['id'];

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'jobfunction_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'jobfunction_id');
    }
}
