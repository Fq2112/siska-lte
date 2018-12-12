<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $guarded = ['id'];

    public function getAgency()
    {
        return $this->belongsTo(Agencies::class, 'agency_id');
    }
}
