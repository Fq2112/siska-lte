<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agencies extends Model
{
    protected $table = 'agencies';

    protected $guarded = ['id'];

    public function getIndustry()
    {
        return $this->belongsTo(Industries::class,'industry_id');
    }

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'agency_id');
    }
}
