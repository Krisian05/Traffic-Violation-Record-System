<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class IncidentChargeType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(fn() => Cache::forget('incident_charge_types'));
        static::deleted(fn() => Cache::forget('incident_charge_types'));
    }

    public function incidentMotorists(): HasMany
    {
        return $this->hasMany(IncidentMotorist::class);
    }
}
