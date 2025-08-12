<?php

namespace App\Http\Controllers;

use App\Models\OverdueClaim;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // If search parameters were submitted
        if ($request->has('id_no') || $request->has('passport_no')) {
            $request->validate([
                'id_no' => 'nullable|string|max:20',
                'passport_no' => 'nullable|string|max:20'
            ]);

            // Search using Eloquent with bank relationship
            $application = Application::with('bank')
                ->where('id_no', $request->id_no)
                ->orWhere('passport_no', $request->passport_no)
                ->first();
            
            if ($application) {
                $claim = OverdueClaim::where('application_no', $application->application_no)->first();
                return view('components.claims.create', [
                    'application' => $application,
                    'claim' => $claim,
                    'searchPerformed' => true
                ]);
            }
            
            return view('components.claims.create', [
                'searchPerformed' => true,
                'application' => null,
                'claim' => null
            ]);
        }
        
        // Initial access - just show search form
        return view('components.claims.create', [
            'searchPerformed' => false,
            'application' => null,
            'claim' => null
        ]);
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

        return redirect()->route('claims.show', $applicationNo)
            ->with('success', 'Claim report saved successfully!');
    }
}