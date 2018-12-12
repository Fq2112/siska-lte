<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLevel extends Model
{
    protected $table = 'job_levels';

    protected $guarded = ['id'];

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'joblevel_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'joblevel_id');
    }
}
