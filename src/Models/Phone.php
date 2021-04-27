<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tsung\NovaUserManagement\Traits\SaveToUpper;

class Phone extends Model
{
    use SaveToUpper;

    protected $table = 'master_phones';

    protected $no_upper = [
        'phone_type'
    ];

    protected $fillable = [
        'type',
        'number',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function phones()
    {
        return $this->morphTo('phone');
    }
}
