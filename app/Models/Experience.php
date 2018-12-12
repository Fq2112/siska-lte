<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = 'experiences';

    protected $guarded = ['id'];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getJobLevel()
    {
        return $this->belongsTo(JobLevel::class, 'joblevel_id');
    }

    public function getJobFunction()
    {
        return $this->belongsTo(JobFunction::class, 'jobfunction_id');
    }

    public function getIndustry()
    {
        return $this->belongsTo(Industries::class, 'industry_id');
    }

    public function getCity()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }

    public function getSalary()
    {
        return $this->belongsTo(Salaries::class, 'salary_id');
    }

    public function getJobType()
    {
        return $this->belongsTo(JobType::class, 'jobtype_id');
    }
}
