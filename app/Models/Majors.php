<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Majors extends Model
{
    protected $table = 'majors';

    protected $guarded = ['id'];

    public function getEducation()
    {
        return $this->hasMany(Education::class, 'major_id');
    }

    public function getVacancy()
    {
        return $this->hasMany(Vacancies::class, 'major_id');
    }
}
