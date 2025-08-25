<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverdueClaim extends Model
{
    use HasFactory;

    protected $table = 'apara_claims';

    protected $casts = [
    'status' => 'string'
];

     protected $attributes = [
    'status' => 'pending'
];

     public static $statusRules = [
    'status' => 'required|in:pending,paid'
];

    protected $fillable = [
        'application_no',
        'bank_name',
        'branch_name',
        'customer_name',
        'customer_address',
        'total_repayments',
        'amount_outstanding',
        'default_reasons',
        'demand_made',
        'demand_letter_path',
        'no_demand_reason',
        'recovery_steps_taken',
        'proposed_steps',
        'additional_info',
        'signature_name',
        'signature_designation',
        'bank_address',
        'report_date',
        'status'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_no', 'application_no');
    }

public function documents()
    {
        return $this->hasOne(ClaimDocument::class, 'claim_id');
    }
}