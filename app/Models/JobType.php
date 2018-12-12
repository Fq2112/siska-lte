<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $table = 'job_types';

    protected $guarded = ['id'];

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'jobtype_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'jobtype_id');
    }
}
