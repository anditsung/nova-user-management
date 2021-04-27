<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tsung\NovaUserManagement\Traits\SaveToUpper;

class Address extends Model
{
    use SaveToUpper;

    protected $table = 'master_addresses';

    protected $no_upper = [
        'address_type'
    ];

    protected $fillable = [
        'type',
        'address',
        'address_type',
        'address_id',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function addresses()
    {
        return $this->morphTo('address');
    }
}
