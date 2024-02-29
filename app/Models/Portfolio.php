<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @method static where(string $string, mixed $id)
 */
class Portfolio extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function details()
    {
        return $this->hasMany(PortfolioDetail::class);
    }

    /**
     * @return HasMany
     */
    public function media()
    {
        return $this->hasMany(PortfolioMedia::class);
    }
}