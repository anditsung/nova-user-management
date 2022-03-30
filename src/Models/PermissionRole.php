<?php

namespace Tsung\NovaUserManagement\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class PermissionRole extends Pivot
{
    // refresh permission cache
    use RefreshesPermissionCache;

//    protected static function boot()
//    {
//        self::saved(function() {
//            ray('saved');
//        });
//
//        self::deleted(function () {
//            ray('deleted');
//        });
//    }
}
