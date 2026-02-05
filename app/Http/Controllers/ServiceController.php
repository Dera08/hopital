<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('hospital_id', Auth::user()->hospital_id)->get();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:services,code',
            'type' => 'required|in:medical,support,technical',
            'consultation_price' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Service::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'consultation_price' => $request->consultation_price ?? 0,
            'icon' => $request->icon ?? 'bi-hospital',
            'color' => $request->color ?? '#3b82f6',
            'location' => $request->location,
            'description' => $request->description,
            'hospital_id' => Auth::user()->hospital_id,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Service créé avec succès.');
    }
}
