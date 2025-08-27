<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimApproval extends Model
{
    protected $fillable = [
        'claim_id',
        'approver_id',
        'approver_name',
        'department',
        'approval_type',
        'status',
    ];

    public function claim()
    {
        return $this->belongsTo(OverdueClaim::class, 'claim_id');
    }
}
