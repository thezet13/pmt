<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'slide_number',
        'title',
        'is_active',
    ];

    public function permissions()
    {
        return $this->hasMany(SlidePermission::class);
    }

    public function statuses()
    {
        return $this->hasMany(SlideStatus::class);
    }
}
