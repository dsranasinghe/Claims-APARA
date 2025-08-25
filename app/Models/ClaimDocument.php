<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        // Section A
        'formal_claim_application',
        'facility_offer_letter',
        'guarantors_bond',
        'guarantors_statement',
        'loan_repayment_schedule',
        'proof_of_disbursement',
        'recovery_actions',
        'kyc_documents',
        
        // Section B1
        'b1_police_complaint',
        'b1_affidavit',
        'b1_tracing_proof',
        
        // Section B2
        'b2_police_complaint',
        'b2_tracing_proof',
        
        // Section C
        'death_certificate',
        
        // Section D
        'medical_certificate_abroad',
        'medical_report_local',
        
        // Section E
        'fraud_evidence',
        
        // Section F
        'refusal_correspondence',
        
        // Section G
        'termination_letter',
        'unemployment_proof',
        
        // Section H
        'new_employment_letter',
        'income_change_evidence'
    ];

    public function claim()
    {
        return $this->belongsTo(OverdueClaim::class, 'claim_id');
    }
}