<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'marketing_request_id',
        'approver_id',
        'status',
        'comment',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    public function marketingRequest()
    {
        return $this->belongsTo(MarketingRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
