<?php

namespace App\Http\Controllers;

use App\Models\Prestation;
use App\Models\Service;
use App\Models\PatientAct;
use App\Models\PrestationPack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestationController extends Controller
{
    public function index()
    {
        $hospitalId = Auth::user()->hospital_id;

        // Statistiques générales
        $stats = [
            'total_prestations' => Prestation::where('hospital_id', $hospitalId)->count(),
            'active_prestations' => Prestation::where('hospital_id', $hospitalId)->where('is_active', true)->count(),
            // POINT 3: Calcul du revenu réel basé sur les actes payés
            'total_revenue' => PatientAct::withoutGlobalScope('hospital_filter')->join('patients', 'patient_acts.patient_id', '=', 'patients.id')
                ->where('patients.hospital_id', $hospitalId)
                ->where('patient_acts.status', 'completed')
                ->sum('patient_acts.actual_price'),
            'payable_prestations' => Prestation::where('hospital_id', $hospitalId)->where('requires_payment', true)->count(),
        ];

        // Statistiques par catégorie
        $categoryStats = Prestation::where('hospital_id', $hospitalId)
            ->selectRaw('category, COUNT(*) as count, SUM(price) as total_price')
            ->groupBy('category')
            ->get()
            ->keyBy('category');

        // Statistiques par service
        $serviceStats = Prestation::with('service')
            ->where('hospital_id', $hospitalId)
            ->selectRaw('service_id, COUNT(*) as count, SUM(price) as total_price')
            ->groupBy('service_id')
            ->with('service')
            ->get();

        // Prestations récentes
        $recentPrestations = Prestation::with('service')
            ->where('hospital_id', $hospitalId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Toutes les prestations pour la liste complète
        $prestations = Prestation::with('service')
            ->where('hospital_id', $hospitalId)
            ->orderBy('name')
            ->get();

        // POINT 4: Récupération des packs pour l'affichage
        $prestationPacks = PrestationPack::where('hospital_id', $hospitalId)
            ->with('prestations')
            ->get();

        return view('prestations.index', compact(
            'prestations',
            'stats',
            'categoryStats',
            'serviceStats',
            'recentPrestations',
            'prestationPacks'
        ));
    }

    public function create()
{
    $hospitalId = Auth::user()->hospital_id;

    // 1. Récupérer les services pour le menu déroulant "Service associé"
    $services = \App\Models\Service::where('hospital_id', $hospitalId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    // 2. RÉSOLUTION DE L'ERREUR : Récupérer les prestations pour le choix des items du Pack
    // On ne prend que les prestations simples (pas les packs eux-mêmes) pour éviter les boucles infinies
    $prestations = \App\Models\Prestation::where('hospital_id', $hospitalId)
        ->where('is_active', true)
        ->where('is_pack', false) // Optionnel : ne lister que les actes simples
        ->orderBy('name')
        ->get();

    return view('prestations.create', compact('services', 'prestations'));
}
  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:20|unique:prestations,code,NULL,id,hospital_id,' . Auth::user()->hospital_id,
        'category' => 'required|string|in:consultation,examen,soins,medicament,hospitalisation',
        'service_id' => 'required|exists:services,id',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'payment_timing' => 'required|in:before,after,upon_completion',
        'requires_approval' => 'boolean',
        'approval_level' => 'nullable|integer|min:1|max:3',
        'is_emergency' => 'boolean',
        'estimated_duration' => 'nullable|integer|min:1',
        'priority' => 'required|in:low,medium,high,urgent',
        'is_pack' => 'boolean',
        'pack_items' => 'required_if:is_pack,1|array', // Obligatoire si c'est un pack
    ]);

    // 1. Création de la prestation principale (ou du Pack)
    $prestation = Prestation::create([
        'name' => $request->name,
        'code' => strtoupper($request->code),
        'category' => $request->category,
        'service_id' => $request->service_id,
        'price' => $request->price,
        'description' => $request->description,
        'hospital_id' => Auth::user()->hospital_id,
        'is_active' => true,
        'requires_payment' => true,
        'payment_timing' => $request->payment_timing,
        'requires_approval' => $request->has('requires_approval'),
        'approval_level' => $request->approval_level,
        'is_emergency' => $request->has('is_emergency'),
        'estimated_duration' => $request->estimated_duration,
        'priority' => $request->priority,
        'is_pack' => $request->has('is_pack'),
    ]);

    // 2. POINT 4 : Si c'est un pack, on crée l'entrée dans PrestationPack et on lie les items
    if ($request->has('is_pack') && $request->is_pack == "1") {
        $pack = \App\Models\PrestationPack::create([
            'hospital_id' => Auth::user()->hospital_id,
            'name' => $prestation->name,
            'description' => $prestation->description,
            'total_price' => $prestation->price,
            'is_active' => true,
        ]);

        // On attache les prestations sélectionnées au pack (table pivot)
        // On suppose une quantité de 1 par défaut pour chaque acte du pack
        $syncData = [];
        foreach ($request->pack_items as $itemId) {
            $syncData[$itemId] = ['quantity' => 1];
        }
        $pack->prestations()->attach($syncData);
    }

    return redirect()->route('prestations.index')->with('success', 'Prestation créée avec succès.');
}

    public function show(Prestation $prestation)
    {
        if ($prestation->hospital_id !== Auth::user()->hospital_id) {
            abort(403);
        }

        $prestation->load('service');
        return view('prestations.show', compact('prestation'));
    }

    public function edit(Prestation $prestation)
    {
        if ($prestation->hospital_id !== Auth::user()->hospital_id) {
            abort(403);
        }

        $services = Service::where('hospital_id', Auth::user()->hospital_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('prestations.edit', compact('prestation', 'services'));
    }

   public function update(Request $request, Prestation $prestation)
{
    if ($prestation->hospital_id !== Auth::user()->hospital_id) {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:20|unique:prestations,code,' . $prestation->id . ',id,hospital_id,' . Auth::user()->hospital_id,
        'category' => 'required|string|in:consultation,examen,soins,medicament,hospitalisation',
        'service_id' => 'required|exists:services,id',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'payment_timing' => 'required|in:before,after,upon_completion',
        'priority' => 'required|in:low,medium,high,urgent',
    ]);

    // On prépare les données et on force les booléens
    // Car si la case est décochée, $request->has() renverra false
    $prestation->update([
        'name' => $request->name,
        'code' => strtoupper($request->code),
        'category' => $request->category,
        'service_id' => $request->service_id,
        'price' => $request->price,
        'description' => $request->description,
        'is_active' => $request->has('is_active'), // <--- Crucial
        'requires_payment' => $request->has('requires_payment'), // <--- Crucial
        'payment_timing' => $request->payment_timing,
        'requires_approval' => $request->has('requires_approval'),
        'approval_level' => $request->approval_level,
        'is_emergency' => $request->has('is_emergency'),
        'estimated_duration' => $request->estimated_duration,
        'priority' => $request->priority,
        'is_pack' => $request->has('is_pack'),
    ]);

    return redirect()->route('prestations.index')->with('success', 'Prestation mise à jour avec succès.');
}

    public function destroy(Prestation $prestation)
    {
        if ($prestation->hospital_id !== Auth::user()->hospital_id) {
            abort(403);
        }

        if ($prestation->appointments()->exists()) {
            return redirect()->route('prestations.index')->with('error', 'Impossible de supprimer cette prestation car elle est utilisée dans des rendez-vous.');
        }

        $prestation->delete();

        return redirect()->route('prestations.index')->with('success', 'Prestation supprimée avec succès.');
    }
}