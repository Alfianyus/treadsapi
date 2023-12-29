<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'body',
        'image',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
