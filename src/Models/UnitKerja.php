<?php

namespace Juniyasyos\ManageUnitKerja\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UnitKerja extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'unit_name',
        'description',
        'slug',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->unit_name);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_unit_kerja', 'unit_kerja_id', 'user_id')->withTimestamps();
    }

    public function imutData(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\ImutData::class, 'imut_data_unit_kerja')
            ->using(\App\Models\ImutDataUnitKerja::class)
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    public function laporanUnitKerjas(): HasMany
    {
        return $this->hasMany(\App\Models\LaporanUnitKerja::class, 'unit_kerja_id');
    }

    public function getUniqueValidationRules(?int $ignoreId = null): array
    {
        return [
            'unit_name' => ['required', 'string', 'max:100', 'unique:unit_kerja,unit_name,' . ($ignoreId ?? 'NULL') . ',id,deleted_at,NULL'],
        ];
    }
}
