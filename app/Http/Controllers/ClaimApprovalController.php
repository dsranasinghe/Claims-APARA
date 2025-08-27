<?php

namespace App\Http\Controllers;

use App\Models\ClaimApproval;
use App\Models\OverdueClaim;
use Illuminate\Http\Request;

class ClaimApprovalController extends Controller
{
    public function approve(Request $request, $id)
{
    $department = strtolower($request->department);
    $validApprovalTypes = [
        'initial_' . $department,
        'final_' . $department
    ];

    $request->validate([
        'department' => 'required|in:Marketing,Operations',
        'approval_type' => 'required|in:' . implode(',', $validApprovalTypes),
        'status' => 'required|in:Approved,Rejected',
        'remarks' => 'nullable|string',
    ]);
    $claim = OverdueClaim::findOrFail($id);

    // Check if approval already exists for this department and step
    $existingApproval = ClaimApproval::where('claim_id', $id)
        ->where('department', $request->department)
        ->where('approval_type', $request->approval_type)
        ->first();

    if ($existingApproval) {
        return back()->with('error', "{$request->approval_type} approval for {$request->department} has already been submitted.");
    }

    // Prevent same user from approving both steps in the same department
    $existingUserApproval = ClaimApproval::where('claim_id', $id)
        ->where('department', $request->department)
        ->where('approver_id', session('employee_id'))
        ->exists();

    if ($existingUserApproval) {
        return back()->with('error', 'You cannot approve both stages.');
    }

    // Restrict premature final approval
    if ($request->approval_type === 'Final') {
        $initialApproved = ClaimApproval::where('claim_id', $id)
            ->where('department', $request->department)
            ->where('approval_type', 'Initial')
            ->where('status', 'Approved')
            ->exists();

        if (!$initialApproved) {
            return back()->with('error', 'Final approval not allowed before Initial approval.');
        }
    }

    // Save approval - MAKE SURE VALUES ARE PROPERLY STRINGIFIED
    ClaimApproval::create([
        'claim_id'      => $id,
        'approver_id'   => session('employee_id'),
        'approver_name' => session('username'),
        'department'    => $request->department, 
        'approval_type' => $request->approval_type, // This should be a string
        'status'        => $request->status,     
        'remarks'       => $request->remarks,
    ]);

    // Update the main claim status based on approvals
    $this->updateClaimStatus($id, $request->department);

    return back()->with(
        'success', 
        "{$request->department} - {$request->approval_type} step has been {$request->status} successfully."
    );
}
    private function updateClaimStatus($claimId, $department)
    {
        $claim = OverdueClaim::findOrFail($claimId);
        $approvals = ClaimApproval::where('claim_id', $claimId)->get();

        // Check if both departments have completed both steps
        $marketingComplete = $this->isDepartmentComplete($approvals, 'Marketing');
        $operationsComplete = $this->isDepartmentComplete($approvals, 'Operations');

        if ($marketingComplete && $operationsComplete) {
            $claim->status = 'Completed';
        } else {
            // Update status based on current department's progress
            $departmentApprovals = $approvals->where('department', $department);
            
            if ($departmentApprovals->where('approval_type', 'Final')->count() > 0) { // Changed
                $claim->status = $department . ' Final ' . $departmentApprovals->where('approval_type', 'Final')->first()->status; // Changed
            } elseif ($departmentApprovals->where('approval_type', 'Initial')->count() > 0) { // Changed
                $claim->status = $department . ' Initial ' . $departmentApprovals->where('approval_type', 'Initial')->first()->status; // Changed
            }
        }

        $claim->save();
    }

    private function isDepartmentComplete($approvals, $department)
    {
        $deptApprovals = $approvals->where('department', $department);
        return $deptApprovals->where('approval_type', 'Initial')->count() > 0 &&  // Changed
               $deptApprovals->where('approval_type', 'Final')->count() > 0;      // Changed
    }
}