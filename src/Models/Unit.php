<?php

namespace Tsung\NovaUserManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Tsung\NovaUserManagement\Traits\SaveToUpper;

class Unit extends Model
{
    // https://www.zoho.com/in/books/kb/gst/unit-code-list.html
    // http://www.svtuition.org/2010/01/list-of-units-of-measurement-for.html

    use SaveToUpper;

    protected $table = 'master_units';

    protected $fillable = [
        'name',
        'abbr',
        'user_id',
        'is_active'
    ];

    protected $no_upper = [
    ];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
