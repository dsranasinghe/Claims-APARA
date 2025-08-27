<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('apara_claims')->onDelete('cascade');

           
            $table->unsignedBigInteger('approver_id'); 
            $table->string('approver_name');           
            $table->string('department');              
            $table->enum('approval_type', ['initial_marketing', 'final_marketing', 'initial_operations', 'final_operations']);
            $table->enum('status', ['approved', 'rejected']);
            $table->timestamps();

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_approvals');
    }
};
