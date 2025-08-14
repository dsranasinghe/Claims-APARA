<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'application';
    protected $primaryKey = 'application_no';
    public $incrementing = false; 

    protected $fillable = [
        'application_no',
        'bank_id',
        'customer_name',
        'customer_address',
        'id_no',
        'passport_no'
        
    ];

    // Relationship to Bank
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

     // Define relationship to OverdueClaim
    public function overdueClaim()
    {
        return $this->hasOne(OverdueClaim::class, 'application_no', 'application_no');
    }
    
}