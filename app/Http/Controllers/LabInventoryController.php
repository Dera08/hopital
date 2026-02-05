<?php

namespace App\Http\Controllers;

use App\Models\LabInventory;
use Illuminate\Http\Request;

class LabInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = LabInventory::where('hospital_id', auth()->user()->hospital_id)
            ->orderBy('name')
            ->get();
            
        return view('lab.inventory.index', compact('inventory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'min_threshold' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'batch_number' => 'nullable|string|max:100',
        ]);

        $validated['hospital_id'] = auth()->user()->hospital_id;

        LabInventory::create($validated);

        return redirect()->back()->with('success', 'Article ajouté au stock.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LabInventory $labInventory)
    {
        // Vérification de l'appartenance à l'hôpital
        if ($labInventory->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'min_threshold' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $labInventory->update($validated);

        return redirect()->back()->with('success', 'Stock mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabInventory $labInventory)
    {
        if ($labInventory->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $labInventory->delete();

        return redirect()->back()->with('success', 'Article supprimé du stock.');
    }
}
