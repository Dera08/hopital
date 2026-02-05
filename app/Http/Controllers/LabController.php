<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LabRequest;

class LabController extends Controller
{
    /**
     * Display the lab technician dashboard.
     */
    public function index()
    {
        $hospitalId = auth()->user()->hospital_id;

        $completedToday = LabRequest::where('hospital_id', $hospitalId)
            ->whereDate('updated_at', now()->toDateString())
            ->where('status', 'completed')
            ->where('is_paid', true) // Only paid
            ->count();

        $pendingRequests = LabRequest::where('hospital_id', $hospitalId)
            ->whereIn('status', ['pending', 'sample_received', 'in_progress', 'to_be_validated'])
            ->where('is_paid', true) // Only paid
            ->with(['doctor', 'service'])
            ->latest()
            ->get();

        return view('lab.dashboard', compact('completedToday', 'pendingRequests'));
    }



    /**
     * Biologist Stats
     */
    public function biologistStats()
    {
        return view('lab.biologist.stats');
    }

    /**
     * List of results to be validated
     */
    public function validationList()
    {
        $resultsToValidate = LabRequest::where('hospital_id', auth()->user()->hospital_id)
            ->where('status', 'to_be_validated')
            ->with(['doctor', 'labTechnician'])
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('lab.biologist.validation', compact('resultsToValidate'));
    }

    /**
     * Validate and publish result (by Biologist)
     */
    public function validateResult(Request $request, $id)
    {
        $labRequest = LabRequest::findOrFail($id);

        $labRequest->update([
            'status' => 'completed',
            'biologist_id' => auth()->id(),
            'validated_at' => now(),
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Résultat validé et publié avec succès.');
    }

    public function worklist(Request $request)
    {
        $hospitalId = auth()->user()->hospital_id;
        $filter = $request->query('filter', 'pending'); // Default to pending/active

        $query = LabRequest::where('hospital_id', $hospitalId)
            ->where('is_paid', true) // Filter: Must be paid first!
            ->with(['doctor', 'service'])
            ->latest();

        if ($filter === 'urgent') {
            // Assuming urgency is stored or implied (e.g. by clinical info or priority field not yet defined, 
            // but for now we might filter by status or create a scope if 'urgency' column exists. 
            // checking migration... if no urgency column, maybe just show all pending for now or specific status)
            // For now, let's just return pending ones as 'urgent' is not fully implemented in schema yet or use a placeholder logic
             $query->whereIn('status', ['pending', 'sample_received', 'in_progress']);
        } elseif ($filter === 'all') {
             $query->whereIn('status', ['pending', 'sample_received', 'in_progress', 'to_be_validated']);
        } else {
            // Default view: active work (received, in progress) + pending
             $query->whereIn('status', ['pending', 'sample_received', 'in_progress']);
        }

        $pendingRequests = $query->get();

        return view('lab.worklist', compact('pendingRequests'));
    }

    public function history(Request $request)
    {
        $hospitalId = auth()->user()->hospital_id;
        
        $query = LabRequest::where('hospital_id', $hospitalId)
            ->where('status', 'completed')
            ->with(['doctor', 'labTechnician', 'biologist'])
            ->latest('completed_at');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_name', 'like', "%$search%")
                  ->orWhere('patient_ipu', 'like', "%$search%")
                  ->orWhere('test_name', 'like', "%$search%");
            });
        }

        if ($request->date) {
            $query->whereDate('completed_at', $request->date);
        }

        $completedRequests = $query->paginate(20);

        return view('lab.history', compact('completedRequests'));
    }

    public function inventory()
    {
        return view('lab.inventory');
    }

    public function updateStatus(Request $request, $id)
    {
        $labRequest = LabRequest::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:sample_received,in_progress,completed',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'lab_technician_id' => auth()->id(),
        ];

        if ($validated['status'] === 'sample_received') {
            $updateData['sample_received_at'] = now();
        } elseif ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
        }

        $labRequest->update($updateData);

        $message = $validated['status'] === 'sample_received' ? 'Échantillon reçu avec succès.' : 'Analyse démarrée.';
        return back()->with('success', $message);
    }

    public function storeResult(Request $request, $id)
    {
        $labRequest = LabRequest::findOrFail($id);
        
        $validated = $request->validate([
            'result' => 'required|string',
        ]);

        $labRequest->update([
            'result' => $validated['result'],
            'status' => 'to_be_validated', // Sends to biologist
            'lab_technician_id' => auth()->id(),
        ]);

        return redirect()->route('lab.worklist')->with('success', 'Résultat enregistré et envoyé pour validation.');
    }
}
