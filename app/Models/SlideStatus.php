<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlideStatus extends Model
{
    protected $fillable = [
        'presentation_month_id',
        'slide_id',
        'status',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function month()
    {
        return $this->belongsTo(PresentationMonth::class, 'presentation_month_id');
    }

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
