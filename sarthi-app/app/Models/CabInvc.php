<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabInvc extends Model
{
    protected $table = 'cab_invc';
    protected $fillable = ['service_request_id', 'amount', 'status', 'issued_at'];
    protected function casts(): array { return ['issued_at' => 'datetime']; }
}
