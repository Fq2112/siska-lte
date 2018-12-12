<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $guarded = ['id'];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDegree()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }

    public function getMajor()
    {
        return $this->belongsTo(Majors::class, 'major_id');
    }
}
