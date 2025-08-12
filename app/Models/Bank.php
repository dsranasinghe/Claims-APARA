<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'bank_id';

    protected $fillable = [
        'bank_name',
        'branch_name'
        // Add other fields as needed
    ];

    // Relationship to Applications
    public function application()
    {
        return $this->hasMany(Application::class, 'bank_id', 'bank_id');
    }
}