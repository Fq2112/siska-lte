<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';

    protected $guarded = ['id'];

    public function getProvince()
    {
        return $this->belongsTo(Provinces::class, 'province_id');
    }

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'city_id');
    }

    public function getExperience()
    {
        return $this->hasMany(Experience::class, 'city_id');
    }
}
