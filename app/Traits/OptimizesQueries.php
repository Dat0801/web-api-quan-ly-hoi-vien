<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

trait OptimizesQueries
{
    /**
     * Scope a query to include eager loading relationships
     */
    public function scopeWithOptimizedRelations($query)
    {
        return $query->with([
            'industry:id,name',
            'field:id,name',
            'market:id,name',
            'club:id,name',
        ]);
    }

    /**
     * Get cached data with fallback
     */
    protected function getCachedData($key, $ttl, $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Clear related caches
     */
    protected function clearRelatedCaches()
    {
        Cache::forget('industries');
        Cache::forget('fields');
        Cache::forget('markets');
        Cache::forget('clubs');
    }

    /**
     * Scope a query to use index hints
     */
    public function scopeUseIndex($query, $index)
    {
        return $query->fromRaw("{$this->getTable()} FORCE INDEX ({$index})");
    }

    /**
     * Scope a query to chunk results
     */
    public function scopeChunked($query, $chunkSize = 100)
    {
        return $query->chunk($chunkSize, function ($items) {
            // Process chunk
        });
    }
} 