<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'price',
    ];


    /**
     * Get all of the photos for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class);
    }

    /**
     * Get the umkm that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }

    /**
     * Scope a query to only include search
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scope($query, $search)
    {
        return $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('price', 'LIKE', '%' . $search . '%');
    }
}
