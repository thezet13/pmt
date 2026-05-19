<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlidePermission extends Model
{
    protected $fillable = [
        'user_id',
        'slide_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }
}
