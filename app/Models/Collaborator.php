<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collaborator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'user_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function timeTrackers(): HasMany
    {
        return $this->hasMany(TimeTracker::class);
    }

    public function getTotalTimeTodayAttribute(): int
    {
        return $this->timeTrackers()
            ->whereDate('start_time', now()->toDateString())
            ->whereNotNull('end_time')
            ->get()
            ->sum(function($tt) { return $tt->duration; });
    }

    public function getTotalTimeThisMonthAttribute(): int
    {
        return $this->timeTrackers()
            ->whereMonth('start_time', now()->month)
            ->whereYear('start_time', now()->year)
            ->whereNotNull('end_time')
            ->get()
            ->sum(function($tt) { return $tt->duration; });
    }

    public function getFormattedTotalTimeTodayAttribute(): string
    {
        $seconds = $this->total_time_today;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function getFormattedTotalTimeThisMonthAttribute(): string
    {
        $seconds = $this->total_time_this_month;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
