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
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'price' => $request->price,
            'hospital_id' => Auth::user()->hospital_id,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Service créé avec succès.');
    }
}
