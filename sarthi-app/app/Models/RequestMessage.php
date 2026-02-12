<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestMessage extends Model
{
    use HasFactory;

    protected $fillable = ['service_request_id', 'sender_id', 'body', 'seen_by_staff_at', 'seen_by_customer_at'];

    protected function casts(): array
    {
        return [
            'seen_by_staff_at' => 'datetime',
            'seen_by_customer_at' => 'datetime',
        ];
    }

    public function request(): BelongsTo { return $this->belongsTo(ServiceRequest::class, 'service_request_id'); }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_id'); }
}
