<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'admin_id', 'user_id', 'status',
    ];

    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
