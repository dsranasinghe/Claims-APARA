<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('claim_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('apara_claims')->onDelete('cascade');
            
            // Section A - Mandatory Documents for all claims
            $table->string('formal_claim_application')->nullable();
            $table->string('facility_offer_letter')->nullable();
            $table->string('guarantors_bond')->nullable();
            $table->string('guarantors_statement')->nullable();
            $table->string('loan_repayment_schedule')->nullable();
            $table->string('proof_of_disbursement')->nullable();
            $table->string('recovery_actions')->nullable();
            $table->string('kyc_documents')->nullable();
            
            // Section B1 - Missing/absconded in abroad
            $table->string('b1_police_complaint')->nullable();
            $table->string('b1_affidavit')->nullable();
            $table->string('b1_tracing_proof')->nullable();
            
            // Section B2 - Missing in Sri Lanka after arrival
            $table->string('b2_police_complaint')->nullable();
            $table->string('b2_tracing_proof')->nullable();
            
            // Section C - Borrower is deceased
            $table->string('death_certificate')->nullable();
            
            // Section D - Medically unfit/critically ill
            $table->string('medical_certificate_abroad')->nullable();
            $table->string('medical_report_local')->nullable();
            
            // Section E - Fraud
            $table->string('fraud_evidence')->nullable();
            
            // Section F - Refusal to pay
            $table->string('refusal_correspondence')->nullable();
            
            // Section G - Loss of job
            $table->string('termination_letter')->nullable();
            $table->string('unemployment_proof')->nullable();
            
            // Section H - Borrower shifted job
            $table->string('new_employment_letter')->nullable();
            $table->string('income_change_evidence')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_documents');
    }
};