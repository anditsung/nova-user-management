<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tsung\NovaUserManagement\Traits\SaveToUpper;

class Note extends Model
{
    use SaveToUpper;

    protected $table = 'master_notes';

    protected $no_upper = [
        'note_type'
    ];

    protected $fillable = [
        'note',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function notes()
    {
        return $this->morphTo('note');
    }
}
