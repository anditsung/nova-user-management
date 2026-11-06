<?php

namespace Tsung\NovaUserManagement\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class PermissionRole extends Pivot
{
    use RefreshesPermissionCache;
}
