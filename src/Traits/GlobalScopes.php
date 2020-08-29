<?php


namespace Tsung\NovaUserManagement\Traits;


trait GlobalScopes
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
