<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tsung\NovaUserManagement\Traits\SaveToUpper;

class Holiday extends Model
{
    use SaveToUpper;

    protected $table = 'master_holidays';

    protected $no_upper = [
    ];

    protected $fillable = [
        'name',
        'date',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
