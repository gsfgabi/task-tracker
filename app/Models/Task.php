<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'collaborator_id',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function timeTrackers(): HasMany
    {
        return $this->hasMany(TimeTracker::class);
    }

    public function getTotalTimeAttribute(): int
    {
        return $this->timeTrackers()
            ->whereNotNull('end_time')
            ->get()
            ->sum(function($tt) { return $tt->duration; });
    }

    public function getFormattedTotalTimeAttribute(): string
    {
        $seconds = $this->total_time;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function hasActiveTimeTracker(): bool
    {
        return $this->timeTrackers()
            ->whereNull('end_time')
            ->exists();
    }
}
