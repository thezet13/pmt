<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentationMonth extends Model
{
    protected $fillable = [
        'name',
        'year',
        'month',
        'is_active',
    ];

    public function statuses()
    {
        return $this->hasMany(SlideStatus::class);
    }
}
