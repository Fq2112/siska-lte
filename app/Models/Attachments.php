<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    protected $table = 'attachments';

    protected $guarded = ['id'];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
