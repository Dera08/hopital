<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Soins - {{ $patient->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { 
            --nurse-primary: #db2777; /* Pink 600 */
            --nurse-secondary: #9333ea; /* Purple 600 */
            --bg-light: #fdf2f8; /* Pink 50 */
        }
        body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; }
        .top-banner { background: linear-gradient(135deg, var(--nurse-primary) 0%, var(--nurse-secondary) 100%); height: 160px; border-radius: 0 0 30px 30px; }
        .main-card { background: white; border-radius: 20px; margin-top: -70px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 25px; border: none; }
        .fiche-card { background: white; border-radius: 18px; border: 1px solid #fbcfe8; margin-bottom: 25px; overflow: hidden; transition: 0.3s; }
        .constante-label { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
        .constante-value { font-size: 1.2rem; font-weight: 800; color: #1e293b; }
        .note-box { background: #fdf2f8; border-radius: 14px; padding: 18px; border-left: 4px solid var(--nurse-primary); font-style: italic; }
        .status-badge { font-size: 0.65rem; font-weight: 800; padding: 5px 12px; border-radius: 8px; text-transform: uppercase; }
        
        .presc-card { background: white; border-radius: 15px; border: 1px solid #fbcfe8; transition: 0.3s; position: relative; overflow: hidden; }
        .presc-card.signed { border-left: 5px solid #10b981; }
        .presc-card.pending { border-left: 5px solid #f59e0b; }
        .med-icon { width: 45px; height: 45px; border-radius: 12px; background: #fdf2f8; color: var(--nurse-primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        
        .nav-pills .nav-link { color: #64748b; font-weight: 700; border-radius: 12px; padding: 10px 20px; }
        .nav-pills .nav-link.active { background-color: var(--nurse-primary) !important; color: white !important; }
    </style>
</head>
<body>

<div class="top-banner p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h5 class="fw-bold text-white mb-0 italic uppercase tracking-tighter">HospitSIS <span class="fw-light opacity-75">Espace Soins</span></h5>
            <span class="badge bg-white/20 text-white border border-white/30 rounded-full px-3 py-1 text-[10px] uppercase font-bold">Mode Lecture</span>
        </div>
        <a href="{{ route('nurse.dashboard') }}" class="btn btn-sm btn-light rounded-pill px-4 fw-bold shadow-lg transition-transform hover:scale-105">
            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
        </a>
    </div>
</div>

<div class="container mb-5">
    <div class="main-card mb-4 border-b-4 border-pink-500">
        <div class="row align-items-center">
            <div class="col-md-auto text-center">
                <div class="rounded-circle bg-gradient-to-br from-pink-500 to-purple-600 text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-xl" style="width:90px; height:90px; font-size:32px; font-weight:900; border: 4px solid white;">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}{{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
            </div>
            <div class="col-md text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                    <h2 class="fw-black text-slate-800 mb-0 uppercase tracking-tighter italic">{{ $patient->name }} {{ $patient->first_name }}</h2>
                    @if($patient->blood_group)
                        <span class="badge bg-pink-100 text-pink-700 border border-pink-200 rounded-lg px-2 py-1 text-xs font-black">{{ $patient->blood_group }}</span>
                    @endif
                </div>
                <div class="text-slate-500 font-bold small flex items-center gap-3">
                    <span><i class="fas fa-fingerprint me-1 text-pink-400"></i> IPU: <span class="text-slate-800">{{ $patient->ipu }}</span></span>
                    <span class="mx-2 text-slate-300">|</span>
                    <span><i class="fas fa-birthday-cake me-1 text-pink-400"></i> {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : '?' }} ANS</span>
                </div>
            </div>
            <div class="col-md-auto mt-3 mt-md-0 text-center text-md-end">
                <div class="bg-pink-50 p-3 rounded-2xl border border-pink-100">
                    <div class="constante-label mb-1">Dernière Mise à jour</div>
                    <div class="fw-black text-pink-600">{{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>
        
        @if($patient->allergies)
        <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-2xl d-flex align-items-center gap-3">
            <div class="bg-red-500 text-white p-2 rounded-xl shadow-lg animate-pulse">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-red-400 uppercase mb-0 tracking-widest">Alerte Allergies</p>
                <p class="fw-bold text-red-700 mb-0">{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : $patient->allergies }}</p>
            </div>
        </div>
        @endif
    </div>

    <ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm border border-pink-100">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-journal">Journal de Soins</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-prescriptions">Traitements Actifs</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-labo">Examens & Labo</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-coords">Fiche Patient</button></li>
    </ul>

    <div class="tab-content">
        {{-- CARNET DE SANTÉ / TIMELINE --}}
        <div class="tab-pane fade show active" id="tab-journal">
            <div class="row">
                <div class="col-lg-12">
                    @forelse($allExams as $exam)
                        @php
                            $isClinical = $exam instanceof \App\Models\ClinicalObservation;
                            $isNurseNote = $isClinical && $exam->type === 'nurse_note';
                            $examType = $isClinical ? $exam->type : 'consultation';
                            
                            $isCriticalTemp = ($exam->temperature >= 38.5 || $exam->temperature <= 35.5);
                            $isCriticalPulse = ($exam->pulse >= 120 || $exam->pulse <= 50);
                            $isCriticalOverall = $exam->is_critical ?? ($isCriticalTemp || $isCriticalPulse);
                        @endphp
                        
                        <div class="fiche-card {{ $isCriticalOverall ? 'border-red-300 bg-red-50/30' : '' }} {{ $isNurseNote ? 'border-indigo-200 bg-indigo-50/30' : '' }} shadow-sm">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-slate-50/50">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $isCriticalOverall ? 'bg-red-600' : ($isNurseNote ? 'bg-indigo-600' : 'bg-pink-600') }} rounded-lg px-3 py-2 fw-bold small shadow-sm">
                                        <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($exam->observation_datetime ?? $exam->created_at)->format('d/m/Y à H:i') }}
                                    </span>
                                    <span class="badge rounded-lg px-3 py-2 fw-bold small border bg-white text-slate-700 shadow-sm">
                                        @if($isNurseNote) <i class="fas fa-hand-holding-medical me-1 text-indigo-500"></i>SOIN INFIRMIER 
                                        @elseif($examType === 'detailed') <i class="fas fa-stethoscope me-1 text-purple-500"></i>EXAMEN MÉDICAL 
                                        @else <i class="fas fa-notes-medical me-1 text-pink-500"></i>SUIVI INFIRMIER @endif
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-tighter">Urgence:</span>
                                    <span class="badge bg-{{ ($exam->urgency ?? '') === 'critique' ? 'red' : (($exam->urgency ?? '') === 'urgent' ? 'orange' : 'blue') }}-100 text-{{ ($exam->urgency ?? '') === 'critique' ? 'red' : (($exam->urgency ?? '') === 'urgent' ? 'orange' : 'blue') }}-700 rounded-pill px-3 py-1 fw-black text-[10px] uppercase border border-current opacity-75">{{ $exam->urgency ?? 'Normale' }}</span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                @if($examType !== 'detailed')
                                    <div class="row g-3 text-center mb-4">
                                        <div class="col-md-3">
                                            <div class="p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                                <div class="constante-label text-pink-500">Température</div>
                                                <div class="constante-value {{ $isCriticalTemp ? 'text-red-600' : '' }}">{{ $exam->temperature ?? '--' }}°C</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                                <div class="constante-label text-purple-500">Pouls</div>
                                                <div class="constante-value {{ $isCriticalPulse ? 'text-red-600' : '' }}">{{ $exam->pulse ?? '--' }} <small class="text-xs opacity-50">BPM</small></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                                <div class="constante-label text-blue-500">Poids / Taille</div>
                                                <div class="constante-value">{{ $exam->weight ?? '--' }}<small class="text-xs">kg</small> / {{ $exam->height ?? '--' }}<small class="text-xs">cm</small></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3 bg-pink-50 border border-pink-100 rounded-2xl shadow-sm">
                                                <div class="constante-label text-slate-400">Responsable</div>
                                                <div class="text-slate-800 font-black text-sm text-truncate mt-1">
                                                    <i class="fas fa-user-md me-1 opacity-50"></i>{{ $exam->user->name ?? $exam->doctor->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest flex items-center gap-2">
                                        <i class="fas fa-quote-left text-pink-300"></i> Observations de Soins
                                    </p>
                                    <div class="text-slate-700 fw-bold italic" style="white-space: pre-wrap; line-height: 1.6;">
                                        {{ $exam->notes ?? $exam->reason ?? 'Aucune observation enregistrée.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-5 text-center rounded-4 border border-pink-100 text-slate-400 shadow-sm font-bold">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-20"></i>
                            <p>Aucun examen ou suivi disponible pour ce patient.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- PRESCRIPTIONS --}}
        <div class="tab-pane fade" id="tab-prescriptions">
            <div class="row g-4">
                @forelse($patient->prescriptions as $p)
                <div class="col-md-6">
                    @if($p->category === 'nurse')
                        {{-- Design Spécial pour Consigne de Soins (Infirmier) --}}
                        <div class="presc-card p-4 border-2 border-indigo-200 bg-indigo-50/20 shadow-lg relative overflow-hidden" style="border-left: 8px solid #6366f1 !important;">
                            <div class="absolute top-0 right-0 p-2 opacity-10">
                                <i class="fas fa-clipboard-list fa-4x text-indigo-600"></i>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="med-icon me-3 bg-indigo-600 text-white shadow-lg border-0">
                                    <i class="fas fa-hand-holding-medical"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-indigo-500 text-[10px] font-black uppercase tracking-widest">Mission Soins • {{ $p->created_at->format('H:i') }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-square text-indigo-400"></i>
                                        <h5 class="fw-black text-indigo-900 mb-0 tracking-tight uppercase italic">Action à effectuer</h5>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-indigo-600 text-white rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest shadow-sm">CONSIGNE</span>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-2xl p-4 mb-4 border-2 border-indigo-100 shadow-inner">
                                <p class="text-[10px] font-black text-indigo-400 uppercase mb-2">Instructions du Docteur</p>
                                <div class="text-indigo-800 font-bold fs-5 leading-relaxed">
                                    {{ $p->medication }}
                                </div>
                                @if($p->instructions)
                                    <div class="mt-3 pt-3 border-top border-indigo-50">
                                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Détails Additionnels</p>
                                        <p class="small text-slate-600 mb-0 font-bold italic">{{ $p->instructions }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-xs font-black text-indigo-600 uppercase tracking-widest"><i class="fas fa-user-md me-1"></i>Dr. {{ $p->doctor->name ?? 'N/A' }}</span>
                                <div class="d-flex gap-2">
                                    @if($p->is_signed)
                                        <div class="text-indigo-500 bg-white rounded-full p-1 shadow-sm"><i class="fas fa-check-circle"></i></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Design Standard pour Ordonnance (Pharmacie) --}}
                        <div class="presc-card p-4 {{ $p->is_signed ? 'signed' : 'pending' }} shadow-sm">
                            <div class="d-flex align-items-center mb-4">
                                <div class="med-icon me-3 shadow-sm border border-pink-100">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $p->created_at->format('d/m/Y à H:i') }}</div>
                                    <h5 class="fw-black text-slate-800 mb-0 tracking-tight">{{ $p->medication }}</h5>
                                </div>
                                <div>
                                    @if($p->category === 'nurse')
                                        <span class="badge bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg px-2 py-1 text-[10px] font-black uppercase tracking-tighter">Consigne de Soins</span>
                                    @else
                                        <span class="badge bg-blue-100 text-blue-700 border border-blue-200 rounded-lg px-2 py-1 text-[10px] font-black uppercase tracking-tighter">Ordonnance</span>
                                    @endif

                                    @if($p->is_signed)
                                        <span class="badge bg-green-100 text-green-700 border border-green-200 rounded-lg px-2 py-1 text-[10px] font-black uppercase tracking-tighter">Signée</span>
                                    @else
                                        <span class="badge bg-orange-100 text-orange-700 border border-orange-200 rounded-lg px-2 py-1 text-[10px] font-black uppercase tracking-tighter">En attente</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 rounded-2xl p-3 mb-4 border border-slate-100">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Instructions Médicales</p>
                                        <p class="small text-slate-600 mb-0 font-bold">{{ $p->instructions }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-xs font-black text-pink-600 uppercase tracking-widest"><i class="fas fa-user-md me-1"></i>Dr. {{ $p->doctor->name ?? 'N/A' }}</span>
                                @if($p->is_signed)
                                    <div class="text-green-500"><i class="fas fa-check-circle"></i></div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 border border-pink-100 shadow-sm text-slate-400 font-bold">
                        <i class="fas fa-prescription fa-3x mb-3 opacity-20"></i>
                        <p>Aucun traitement prescrit pour le moment.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- LABO / ANALYSES --}}
        <div class="tab-pane fade" id="tab-labo">
            <div class="row g-4">
                @forelse($patient->labRequests as $req)
                <div class="col-md-6">
                    <div class="presc-card p-4 {{ $req->status === 'completed' ? 'signed' : 'pending' }} shadow-sm">
                        <div class="d-flex align-items-center mb-4">
                            <div class="med-icon me-3 bg-purple-50 text-purple-600 shadow-sm border border-purple-100">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $req->created_at->format('d/m/Y') }}</div>
                                <h5 class="fw-black text-slate-800 mb-0 tracking-tight">{{ $req->test_name }}</h5>
                            </div>
                            <div>
                                <span class="badge bg-{{ $req->status === 'completed' ? 'green' : 'orange' }}-100 text-{{ $req->status === 'completed' ? 'green' : 'orange' }}-700 border border-{{ $req->status === 'completed' ? 'green' : 'orange' }}-200 rounded-lg px-2 py-1 text-[10px] font-black uppercase tracking-tighter">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </div>
                        </div>
                        
                        @if($req->clinical_info)
                        <div class="bg-purple-50 rounded-2xl p-3 mb-4 border border-purple-100">
                            <p class="text-[10px] font-black text-purple-400 uppercase mb-1">Renseignements Cliniques</p>
                            <p class="small text-purple-800 mb-0 font-bold italic">{{ $req->clinical_info }}</p>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs font-black text-purple-600 uppercase tracking-widest"><i class="fas fa-user-md me-1 text-purple-400"></i>Prescrit par: Dr. {{ $req->doctor->name ?? 'N/A' }}</span>
                            @if($req->result_file)
                                <a href="{{ asset('storage/'.$req->result_file) }}" target="_blank" class="px-4 py-2 bg-purple-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-purple-700 transition-all">
                                    <i class="fas fa-file-pdf me-1"></i>Résultat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 border border-pink-100 shadow-sm text-slate-400 font-bold">
                        <i class="fas fa-vials fa-3x mb-3 opacity-20"></i>
                        <p>Aucune analyse ou imagerie demandée.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- COORDONNÉES --}}
        <div class="tab-pane fade" id="tab-coords">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white border-b-4 border-pink-500">
                <h4 class="fw-black text-slate-800 mb-5 uppercase tracking-tighter italic flex items-center gap-2">
                    <i class="fas fa-id-card text-pink-500"></i> Dossier d'Identification
                </h4>
                <div class="row g-5">
                    <div class="col-md-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Nom Complet</label>
                        <p class="fw-black text-slate-700 fs-5 mb-0">{{ $patient->name }} {{ $patient->first_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Contact Téléphonique</label>
                        <p class="fw-black text-slate-700 fs-5 mb-0">{{ $patient->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Couleur Groupe Sanguin</label>
                        <p class="fw-black text-red-600 fs-4 mb-0"><i class="fas fa-tint me-2"></i>{{ $patient->blood_group ?? '??' }}</p>
                    </div>
                    <div class="col-12">
                        <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                            <label class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2 block">Historique des Allergies</label>
                            <p class="fw-black text-red-800 mb-0 fs-5 italic">
                                {{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : ($patient->allergies ?? 'AUCUNE ALLERGIE SIGNALÉE') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Antécédents du Patient</label>
                            <p class="text-slate-700 mb-0 font-bold fs-6 tracking-tight leading-relaxed transition-all">
                                {{ $patient->medical_history ?? 'Aucun antécédent médical enregistré dans le dossier.' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Adresse Résidentielle</label>
                        <p class="fw-bold text-slate-600 mb-0">{{ $patient->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Dernière Consultation</label>
                        <p class="fw-bold text-slate-600 mb-0">{{ $patient->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
