<?php


namespace Tsung\NovaUserManagement\Traits;


trait GlobalScopes
{
    public function scopeActives($query)
    {
        return $query->where('is_active', true);
    }
}
