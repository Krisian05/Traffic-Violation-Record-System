<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentMedia extends Model
{
    protected $fillable = [
        'incident_id',
        'file_path',
        'media_type',
        'caption',
    ];

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function isImage(): bool
    {
        return str_ends_with(strtolower($this->file_path), '.pdf') === false;
    }
}
