<?php

namespace App\Models\Site;

use App\Traits\Loggable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $slug
 */
class Province extends Model
{
    use Filterable, HasFactory, Sluggable, Loggable;

    protected $fillable = [
        'name',
        'region_id',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'updated_at',
        'created_at',
    ];

    /**
     * Get the region that owns this province.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeProvinceBelongToRegion(Builder $query, $regionId)
    {
        return $query->whereHas('region', function ($q) use ($regionId) {
            $q->whereIn('id', [$regionId]);
        });
    }
}
