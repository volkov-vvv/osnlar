<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaxSentMessage extends Model
{
    protected $table = 'max_sent_messages';

    protected $fillable = [
        'chat_id', 'message_id', 'user_id', 'lid_id', 'text', 'max_user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
