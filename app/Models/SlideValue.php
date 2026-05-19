<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlideValue extends Model
{
    protected $fillable = [
        'presentation_month_id',
        'slide_id',
        'values_json',
        'updated_by',
    ];

    protected $casts = [
        'values_json' => 'array',
    ];

    public function month()
    {
        return $this->belongsTo(PresentationMonth::class, 'presentation_month_id');
    }

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
