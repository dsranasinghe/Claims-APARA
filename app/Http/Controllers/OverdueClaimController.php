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
    ->whereHas('bank', function($query) {
        $query->when(session('bank_id'), function($q) {
            $q->where('bank_id', session('bank_id'));
        });
    })
    ->where(function($query) use ($request) {
        $query->when($request->id_no, function($q) use ($request) {
            $q->where('id_no', 'like', '%'.$request->id_no.'%');
        })
        ->when($request->passport_no, function($q) use ($request) {
            $q->orWhere('passport_no', 'like', '%'.$request->passport_no.'%');
        });
    })
    ->with(['bank', 'overdueClaim'])
    ->get();

        // Get pending applications
        $pendingApplications = OverdueClaim::where('status', 'pending')
            ->whereHas('application', function($query) use ($request) {
                $query->when($request->id_no, function($q) use ($request) {
                    $q->where('id_no', 'like', '%'.$request->id_no.'%');
                })
                ->when($request->passport_no, function($q) use ($request) {
                    $q->orWhere('passport_no', 'like', '%'.$request->passport_no.'%');
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
            'default_reason' => 'nullable|string|max:255',
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
                'default_reason' => $validated['default_reason'] ?? null,
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
        $query->where(function($q) use ($request) {
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

public function submit($applicationNo)
{
    // Validate application number format
    if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
        abort(400, 'Invalid application number format');
    }

    // Fetch the claim with documents
    $claim = OverdueClaim::with('documents')->where('application_no', $applicationNo)->firstOrFail();
    
    // Check if all required documents are uploaded
    $requiredDocuments = $this->getRequiredDocuments($claim->default_reason);
    $uploadedCount = 0;
    
    if ($claim->documents) {
        foreach ($requiredDocuments as $documentField) {
            if (!empty($claim->documents->$documentField)) {
                $uploadedCount++;
            }
        }
    }
    
    // If not all documents are uploaded, redirect back with error
    if ($uploadedCount < count($requiredDocuments)) {
        return redirect()->route('claims.documents', $applicationNo)
            ->with('error', 'Please upload all required documents before submitting.');
    }
    
    // Update claim status to submitted
    $claim->update(['status' => 'submitted']);
    
    return redirect()->route('claims.pending')
        ->with('success', 'Application #' . $applicationNo . ' has been submitted successfully!');
}

// Helper method to get required documents based on default reason
private function getRequiredDocuments($defaultReason)
{
    $required = [
        'formal_claim_application',
        'facility_offer_letter',
        'guarantors_bond',
        'guarantors_statement',
        'loan_repayment_schedule',
        'proof_of_disbursement',
        'recovery_actions',
        'kyc_documents'
    ];
    
    // Add documents based on default reason
    switch ($defaultReason) {
        case 'missing_abroad':
            array_push($required, 'b1_police_complaint', 'b1_affidavit', 'b1_tracing_proof');
            break;
        case 'missing_local':
            array_push($required, 'b2_police_complaint', 'b2_tracing_proof');
            break;
        case 'deceased':
            array_push($required, 'death_certificate');
            break;
        case 'medically_unfit':
            array_push($required, 'medical_certificate_abroad', 'medical_report_local');
            break;
        case 'fraud':
            array_push($required, 'fraud_evidence');
            break;
        case 'refusal_pay':
            array_push($required, 'refusal_correspondence');
            break;
        case 'job_loss':
            array_push($required, 'termination_letter', 'unemployment_proof');
            break;
        case 'job_shift':
            array_push($required, 'new_employment_letter', 'income_change_evidence');
            break;
    }
    
    return $required;
}

public function uploadSpecificDocument(Request $request, $applicationNo)
{
    // Validate application number format
    if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
        if ($request->ajax()) {
            return response()->json(['error' => 'Invalid application number format'], 400);
        }
        abort(400, 'Invalid application number format');
    }

    // Fetch the claim
    $claim = OverdueClaim::where('application_no', $applicationNo)->firstOrFail();

    // Validate the request
    $request->validate([
        'document_type' => 'required|string',
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
    ]);

    try {
        // Process the uploaded file
        $file = $request->file('document');
        $path = $file->store('claims/documents/' . $applicationNo, 'public');

        // Get or create claim documents record
        $claimDocuments = ClaimDocument::firstOrCreate(['claim_id' => $claim->id]);

        // Update the specific document column
        $documentType = $request->document_type;
        $claimDocuments->update([
            $documentType => $path
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Document uploaded successfully!']);
        }

        return redirect()->route('claims.documents', $applicationNo)
            ->with('success', 'Document uploaded successfully!');

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => 'Error uploading document: ' . $e->getMessage()], 500);
        }

        return redirect()->route('claims.documents', $applicationNo)
            ->with('error', 'Error uploading document: ' . $e->getMessage());
    }
}


public function deleteDocument($applicationNo, $documentType)
{
    // Validate application number format
    if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
        return response()->json(['error' => 'Invalid application number format'], 400);
    }

    // Validate document type
    $validDocumentTypes = [
        'formal_claim_application', 'facility_offer_letter', 'guarantors_bond', 
        'guarantors_statement', 'loan_repayment_schedule', 'proof_of_disbursement',
        'recovery_actions', 'kyc_documents', 'b1_police_complaint', 'b1_affidavit',
        'b1_tracing_proof', 'b2_police_complaint', 'b2_tracing_proof', 'death_certificate',
        'medical_certificate_abroad', 'medical_report_local', 'fraud_evidence',
        'refusal_correspondence', 'termination_letter', 'unemployment_proof',
        'new_employment_letter', 'income_change_evidence'
    ];

    if (!in_array($documentType, $validDocumentTypes)) {
        return response()->json(['error' => 'Invalid document type'], 400);
    }

    // Fetch the claim with documents
    $claim = OverdueClaim::with('documents')->where('application_no', $applicationNo)->firstOrFail();

    if (!$claim->documents || empty($claim->documents->$documentType)) {
        return response()->json(['error' => 'Document not found'], 404);
    }

    try {
        $filePath = $claim->documents->$documentType;

        // Check if file exists before trying to delete
        if (Storage::disk('public')->exists($filePath)) {
            // Delete the file from storage
            Storage::disk('public')->delete($filePath);
        }

        // Update the document record to remove the file path
        $claim->documents->update([
            $documentType => null
        ]);

        return response()->json(['success' => 'Document deleted successfully']);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error deleting document: ' . $e->getMessage()], 500);
    }
}

public function checkDocument(Request $request, $applicationNo)
{
    // Validate application number format
    if (!preg_match('/^[A-Z0-9\-]+$/', $applicationNo)) {
        return response()->json(['error' => 'Invalid application number format'], 400);
    }

    // Fetch the claim with documents
    $claim = OverdueClaim::with('documents')->where('application_no', $applicationNo)->firstOrFail();

    if (!$claim->documents) {
        return response()->json(['uploadedCount' => 0]);
    }

    // Get required documents based on default reason
    $requiredDocuments = $this->getRequiredDocuments($claim->default_reason);
    $uploadedCount = 0;

    foreach ($requiredDocuments as $documentField) {
        if (!empty($claim->documents->$documentField)) {
            $uploadedCount++;
        }
    }

    return response()->json(['uploadedCount' => $uploadedCount]);
}
}