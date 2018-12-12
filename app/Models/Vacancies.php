<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancies extends Model
{
    protected $table = 'vacancy';

    protected $guarded = ['id'];

    public function getAgency()
    {
        return $this->belongsTo(Agencies::class, 'agency_id');
    }

    public function getCity()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }

    public function getJobType()
    {
        return $this->belongsTo(JobType::class, 'jobtype_id');
    }

    public function getIndustry()
    {
        return $this->belongsTo(Industries::class, 'industry_id');
    }

    public function getJobLevel()
    {
        return $this->belongsTo(JobLevel::class, 'joblevel_id');
    }

    public function getSalary()
    {
        return $this->belongsTo(Salaries::class, 'salary_id');
    }

    public function getDegree()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }

    public function getMajor()
    {
        return $this->belongsTo(Majors::class, 'major_id');
    }

    public function getJobFunction()
    {
        return $this->belongsTo(JobFunction::class, 'jobfunction_id');
    }

    public function getApplication()
    {
        return $this->hasMany(Applications::class,'vacancy_id');
    }
}
