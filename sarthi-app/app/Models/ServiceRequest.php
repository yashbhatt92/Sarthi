<?php

namespace App\Models;

use App\Domain\Enums\ServiceRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_id',
        'assigned_staff_id',
        'status',
        'title',
        'description',
        'chat_enabled',
        'chat_closed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServiceRequestStatus::class,
            'chat_enabled' => 'boolean',
            'chat_closed_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function customer(): BelongsTo { return $this->belongsTo(User::class, 'customer_id'); }
    public function assignedStaff(): BelongsTo { return $this->belongsTo(User::class, 'assigned_staff_id'); }
    public function messages(): HasMany { return $this->hasMany(RequestMessage::class); }
}
