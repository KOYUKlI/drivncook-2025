<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasUlidRouteKey
{
    // Ensure models use the 'ulid' column for route model binding
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    protected static function bootHasUlidRouteKey(): void
    {
        static::creating(function ($model) {
            if (empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }
}
