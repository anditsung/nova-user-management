<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tsung\NovaUserManagement\Traits\SaveToUpper;

class Bank extends Model
{

    use SaveToUpper;

    protected $table = 'master_banks';

    protected $no_upper = [
        'bank_type'
    ];

    protected $fillable = [
        'name',
        'account',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function banks()
    {
        return $this->morphTo('bank');
    }
}
