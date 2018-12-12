<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    protected $table = 'applications';

    protected $guarded = ['id'];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getVacancy()
    {
        return $this->belongsTo(Vacancies::class,'vacancy_id');
    }
}
