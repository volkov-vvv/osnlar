<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaxSubscription extends Model
{
    protected $table = 'max_subscriptions';

    protected $fillable = [
        'user_id', 'max_user_id', 'chat_id', 'subscribed_at',
    ];

    protected function casts(): array
    {
        return ['subscribed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('subscribed_at');
    }
}
