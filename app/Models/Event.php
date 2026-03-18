<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Registration::class);
    }

    public function isFull(): bool
    {
        return $this->registrations()->count() >= $this->capacity;
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        if ($this->start_date > $now) return 'upcoming';
        if ($this->end_date < $now) return 'past';
        return 'ongoing';
    }
}
