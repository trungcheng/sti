<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait CanBeDisabled
 * @package App\Models
 * @method Builder active()
 */
trait CanBeDisabledTrait
{
    /**
     * Check instance is active or not
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->disabled_at === null;
    }

    /**
     * Check instance is disabled or not
     *
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->isActive();
    }

    /**
     * Scope active
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('disabled_at');
    }
}
