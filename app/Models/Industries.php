<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industries extends Model
{
    protected $table = 'industries';

    protected $guarded = ['id'];

    public function getAgency()
    {
        return $this->hasMany(Agencies::class, 'industry_id');
    }

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'industry_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'industry_id');
    }
}
