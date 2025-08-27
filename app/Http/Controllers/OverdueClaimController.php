<?php

namespace App\Http\Controllers;

use App\Models\OverdueClaim;
use App\Models\ClaimDocument;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class OverdueClaimController extends Controller
{
    public function show($applicationNo)
    {
        // Validate application number format
        if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
            abort(400, 'Invalid application number format');
        }

        // Fetch application with bank relationship using Eloquent
        $application = Application::with('bank')
            ->where('application_no', $applicationNo)
            ->first();

        if (!$application) {
            abort(404, 'Application not found');
        }

        // Get existing claim if any
        $claim = OverdueClaim::where('application_no', $applicationNo)->first();

        return view('components.claims.create', [
            'application' => $application,
            'claim' => $claim,
            'searchPerformed' => false
        ]);
    }

    public function create(Request $request)
    {
        if ($request->has('bank_id')) {
            session(['bank_id' => $request->bank_id]);
        }

        $username = $request->username ?? session('username');
        session([
            'username' => $username,
            'employee_id' => $request->employee_id ?? session('employee_id'),
            'department' => $request->department ?? session('department'),
            'bank_id' => $request->bank_id ?? session('bank_id'),
        ]);
        // If search parameters were submitted
        if ($request->has('id_no') || $request->has('passport_no')) {
            $request->validate([
                'id_no' => 'nullable|string|max:20',
                'passport_no' => 'nullable|string|max:20'
            ]);

            // Search for applications
            $applications = Application::query()
                ->whereHas('bank', function ($query) {
                    $query->when(session('bank_id'), function ($q) {
                        $q->where('bank_id', session('bank_id'));
                    });
                })
                ->where(function ($query) use ($request) {
                    $query->when($request->id_no, function ($q) use ($request) {
                        $q->where('id_no', 'like', '%' . $request->id_no . '%');
                    })
                        ->when($request->passport_no, function ($q) use ($request) {
                            $q->orWhere('passport_no', 'like', '%' . $request->passport_no . '%');
                        });
                })
                ->with(['bank', 'overdueClaim'])
                ->get();

            // Get pending applications
            $pendingApplications = OverdueClaim::where('status', 'pending')
                ->whereHas('application', function ($query) use ($request) {
                    $query->when($request->id_no, function ($q) use ($request) {
                        $q->where('id_no', 'like', '%' . $request->id_no . '%');
                    })
                        ->when($request->passport_no, function ($q) use ($request) {
                            $q->orWhere('passport_no', 'like', '%' . $request->passport_no . '%');
                        });
                })
                ->with('application')
                ->get();

            // If we found a matching application
            if ($applications->count() === 1) {
                return view('components.claims.create', [
                    'application' => $applications->first(),
                    'claim' => $applications->first()->overdueClaim,
                    'searchPerformed' => true,
                    'pendingApplications' => $pendingApplications
                ]);
            }

            return view('components.claims.create', [
                'username' => $username,
                'searchPerformed' => false,
                'searchPerformed' => true,
                'application' => null,
                'claim' => null,
                'pendingApplications' => $pendingApplications
            ]);
        }

        // Initial access - just show search form
        return view('components.claims.create', [
            'searchPerformed' => false,
            'application' => null,
            'claim' => null,
            'pendingApplications' => collect() // Empty collection
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid'
        ]);

        $claim = OverdueClaim::findOrFail($id);
        $claim->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function store(Request $request, $applicationNo)
    {
        $validated = $request->validate([
            'total_repayments' => 'required|numeric|min:0',
            'amount_outstanding' => 'required|numeric|min:0',
            'default_reasons' => 'required|string|max:1000',
            'demand_made' => 'required|boolean',
            'demand_letter' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'no_demand_reason' => 'nullable|string|max:500|required_if:demand_made,false',
            'recovery_steps_taken' => 'required|string|max:1000',
            'proposed_steps' => 'required|string|max:1000',
            'additional_info' => 'nullable|string|max:1000',
            'signature_name' => 'required|string|max:100',
            'signature_designation' => 'required|string|max:100',
            'bank_address' => 'required|string|max:500',
            'report_date' => 'required|date',
        ]);

        // Handle file upload
        $demandLetterPath = null;
        if ($request->hasFile('demand_letter')) {
            $demandLetterPath = $request->file('demand_letter')
                ->store('claims/demand-letters', 'public');
        }

        // Get application data to include in claim
        $application = Application::with('bank')
            ->where('application_no', $applicationNo)
            ->first();

        // Create or update claim
        OverdueClaim::updateOrCreate(
            ['application_no' => $applicationNo],
            [
                'bank_name' => $application->bank->bank_name,
                'branch_name' => $application->bank->branch_name,
                'customer_name' => $application->customer_name,
                'customer_address' => $application->customer_address,
                'total_repayments' => $validated['total_repayments'],
                'amount_outstanding' => $validated['amount_outstanding'],
                'default_reasons' => $validated['default_reasons'],
                'demand_made' => $validated['demand_made'],
                'demand_letter_path' => $demandLetterPath,
                'no_demand_reason' => $validated['no_demand_reason'] ?? null,
                'recovery_steps_taken' => $validated['recovery_steps_taken'],
                'proposed_steps' => $validated['proposed_steps'],
                'additional_info' => $validated['additional_info'] ?? null,
                'signature_name' => $validated['signature_name'],
                'signature_designation' => $validated['signature_designation'],
                'bank_address' => $validated['bank_address'],
                'report_date' => $validated['report_date'],
            ]
        );

        return redirect()->route('claims.create')
            ->with('success', 'Claim saved successfully!')
            ->withInput($request->only(['id_no', 'passport_no']));
    }


    public function pending(Request $request)
    {
        $query = OverdueClaim::query()
            ->orderBy('created_at', 'desc');

        // Optional: Add search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('application_no', 'like', "%{$request->search}%")
                    ->orWhere('customer_name', 'like', "%{$request->search}%")
                    ->orWhere('amount_outstanding', 'like', "%{$request->search}%");
            });
        }

        return view('components.claims.pending', [
            'pendingApplications' => $query->paginate(10)
        ]);
    }

    public function documents($applicationNo)
    {
        // Validate application number format
        if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
            abort(400, 'Invalid application number format');
        }

        // Fetch the claim with documents
        $claim = OverdueClaim::with('documents')->where('application_no', $applicationNo)->firstOrFail();

        // Use the correct view path that matches your file location
        return view('components.claims.documents', compact('claim'));
    }

    public function uploadDocuments(Request $request, $applicationNo)
    {
        // Validate application number format
        if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
            abort(400, 'Invalid application number format');
        }

        // Fetch the claim
        $claim = OverdueClaim::where('application_no', $applicationNo)->firstOrFail();

        // Validate the request
        $request->validate([
            'document_type' => 'required|string|in:formal_claim_application,facility_offer_letter,guarantors_bond,guarantors_statement,loan_repayment_schedule,proof_of_disbursement,recovery_actions,kyc_documents,b1_police_complaint,b1_affidavit,b1_tracing_proof,b2_police_complaint,b2_tracing_proof,death_certificate,medical_certificate_abroad,medical_report_local,fraud_evidence,refusal_correspondence,termination_letter,unemployment_proof,new_employment_letter,income_change_evidence',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // Process the uploaded file
        $file = $request->file('document');
        $path = $file->store('claims/documents/' . $applicationNo, 'public');

        // Get or create claim documents record
        $claimDocuments = ClaimDocument::firstOrCreate(['claim_id' => $claim->id]);

        // Update the specific document column based on document type
        $documentType = $request->document_type;
        $claimDocuments->update([
            $documentType => $path
        ]);

        // FIXED: Use the correct route name
        return redirect()->route('claims.documents', $applicationNo)
            ->with('success', 'Document uploaded successfully!');
    }

   public function reportOfDefault()
{
    // Fetch all overdue claims with approvals eager loaded, ordered by report_date
    $claims = OverdueClaim::with('approvals')->orderBy('report_date', 'desc')->get();

    // Map database fields to the keys your Blade expects
    $reports = $claims->map(function ($claim) {
        // Get department from session (or you might need to pass this differently)
        $department = session('department');
        
        // Get approvals for this department
        $departmentApprovals = $claim->approvals->where('department', $department);
        
        $initialApproval = $departmentApprovals->where('step', 'Initial')->first();
        $finalApproval = $departmentApprovals->where('step', 'Final')->first();

        return [
            'id' => $claim->id,
            'debtor' => $claim->customer_name,
            'bank' => $claim->bank_name,
            'branch' => $claim->branch_name,
            'submitted_date' => $claim->report_date,
            'initial_approver' => $initialApproval ? $initialApproval->approver_name : '-',
            'final_approver' => $finalApproval ? $finalApproval->approver_name : '-',
            'status' => ucfirst($claim->status),
            'letter_of_demand' => $claim->demand_letter_path ? asset('storage/' . $claim->demand_letter_path) : null,
            // Add the approvals relationship for the view to use
            'approvals' => $claim->approvals
        ];
    });

    return view('pages.reportofdefault', compact('reports'));
}
}
