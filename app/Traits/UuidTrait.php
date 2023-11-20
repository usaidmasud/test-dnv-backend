<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UuidTrait
{
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->keyType = 'string';
            $model->incrementing = false;
            $model->casts = [
                'id' => 'string'
            ];
            $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: (string) Str::orderedUuid();
        });
    }
    /**
     * getIncrementing
     *
     * @return void
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * getKeyType
     *
     * @return void
     */
    public function getKeyType()
    {
        return 'string';
    }
}
