<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitSIS - {{ $patient->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    
    <style>
        :root { --med-primary: #4e73df; --bg-light: #f8f9fc; --danger-med: #e74a3b; --success-med: #1cc88a; }
        body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; }
        .top-banner { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); height: 160px; border-radius: 0 0 30px 30px; }
        .main-card { background: white; border-radius: 20px; margin-top: -70px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 25px; border: none; }
        .fiche-card { background: white; border-radius: 18px; border: 1px solid #e3e6f0; margin-bottom: 25px; overflow: hidden; transition: 0.3s; }
        .critical-fiche { border-left: 8px solid var(--danger-med) !important; box-shadow: 0 0 20px rgba(231, 74, 59, 0.25) !important; }
        .constante-label { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
        .constante-value { font-size: 1.2rem; font-weight: 800; color: #1e293b; }
        .note-box { background: #f8fafc; border-radius: 14px; padding: 18px; border-left: 4px solid var(--med-primary); font-style: italic; }
        .btn-circle { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; border: none; transition: 0.3s; cursor: pointer; }
        .status-badge { font-size: 0.65rem; font-weight: 800; padding: 5px 12px; border-radius: 8px; text-transform: uppercase; }
        
        /* Nouveau style pour les prescriptions en mode "Card" */
        .presc-card { background: white; border-radius: 15px; border: 1px solid #eef2f7; transition: 0.3s; position: relative; overflow: hidden; }
        .presc-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .presc-card.signed { border-left: 5px solid var(--success-med); }
        .presc-card.pending { border-left: 5px solid #f6c23e; }
        .med-icon { width: 45px; height: 45px; border-radius: 12px; background: #f0f4ff; color: var(--med-primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    </style>
</head>
<body>

<div class="top-banner p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-white mb-0">HospitSIS <span class="fw-light text-white-50">médical</span></h5>
        <a href="{{ route('medecin.dashboard') }}" class="btn btn-sm btn-light rounded-pill px-4 fw-bold">Retour</a>
    </div>
</div>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 fw-bold">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="main-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-auto text-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px; height:80px; font-size:28px; font-weight:bold; border: 4px solid white;">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}{{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
            </div>
            <div class="col-md text-center text-md-start">
                <h2 class="fw-bold mb-1">{{ $patient->name }} {{ $patient->first_name }}
                    @if($patient->allergies)
                    <button class="btn btn-danger btn-sm rounded-pill ms-2" onclick="Swal.fire({title:'⚠️ ALLERGIES', text:'{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : $patient->allergies }}', icon:'error'})">ALLERGIES</button>
                    @endif
                </h2>
                <div class="text-muted small">IPU: {{ $patient->ipu }} • {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : '?' }} ans</div>
            </div>
            <div class="col-md-auto d-flex gap-2 mt-3 mt-md-0 justify-content-center">
                <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" onclick="confirmArchive()">
                    <i class="fas fa-check-double me-2"></i>Terminer
                </button>

                <a href="{{ route('prescriptions.create', ['patient_id' => $patient->id]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-prescription me-2"></i>Nouvelle Prescription
                </a>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddExamen">
                    <i class="fas fa-plus-circle me-2"></i>Examen
                </button>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm d-inline-flex">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-journal">Carnet de Santé</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-prescriptions">Prescriptions</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-coords">Coordonnées</button></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-journal">
            {{-- Boutons de partage et modification --}}
            <div class="mb-6 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                <h4 class="text-sm font-bold text-yellow-800 mb-3">Actions sur le dossier médical :</h4>
                <div class="flex gap-4">
                    <form action="{{ route('medical_records.share', $patientVitals->first()->id ?? '#') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                <polyline points="16,6 12,2 8,6"/>
                                <line x1="12" y1="2" x2="12" y2="15"/>
                            </svg>
                            Partager au patient
                        </button>
                    </form>
                    <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-all shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Modifier dans le carnet
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    @forelse($patientVitals as $vital)
                        @php
                            $isCriticalTemp = ($vital->temperature >= 38.5 || $vital->temperature <= 35.5);
                            $isCriticalPulse = ($vital->pulse >= 120 || $vital->pulse <= 50);
                            $isCriticalOverall = $isCriticalTemp || $isCriticalPulse;
                        @endphp
                        <div class="fiche-card {{ $isCriticalOverall ? 'critical-fiche' : '' }}">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <span class="badge {{ $isCriticalOverall ? 'bg-danger' : 'bg-light text-primary' }} rounded-pill px-3 py-2 fw-bold small">
                                    {{ \Carbon\Carbon::parse($vital->created_at)->format('d/m/Y à H:i') }}
                                </span>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-{{ $vital->urgency === 'critique' ? 'danger' : ($vital->urgency === 'urgent' ? 'warning' : 'info') }} rounded-pill px-2 py-1 fw-bold small">{{ ucfirst($vital->urgency) }}</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="row g-0 text-center border rounded-4 bg-light mb-3 overflow-hidden">
                                    <div class="col border-end p-2">
                                        <div class="constante-label">Temp</div>
                                        <div class="constante-value {{ $isCriticalTemp ? 'text-danger' : '' }}">{{ $vital->temperature ?? '--' }}°</div>
                                    </div>
                                    <div class="col border-end p-2">
                                        <div class="constante-label">Pouls</div>
                                        <div class="constante-value {{ $isCriticalPulse ? 'text-danger' : '' }}">{{ $vital->pulse ?? '--' }}</div>
                                    </div>
                                    <div class="col border-end p-2">
                                        <div class="constante-label">TA</div>
                                        <div class="constante-value">{{ $vital->blood_pressure ?? '--' }}</div>
                                    </div>
                                    <div class="col p-2">
                                        <div class="constante-label">Urgence</div>
                                        <div class="constante-value">{{ ucfirst($vital->urgency) }}</div>
                                    </div>
                                </div>
                                <div class="note-box small text-dark">"{{ $vital->reason ?? 'Aucune note.' }}"</div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-5 text-center rounded-4 border text-muted">Aucun examen enregistré.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-prescriptions">
            <div class="row g-3">
                @forelse($patient->prescriptions->sortByDesc('created_at') as $p)
                <div class="col-md-6">
                    <div class="presc-card p-3 {{ $p->is_signed ? 'signed' : 'pending' }}">
                        <div class="d-flex align-items-center mb-3">
                            <div class="med-icon me-3">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-bold">{{ $p->created_at->format('d/m/Y') }}</div>
                                <h6 class="fw-bold mb-0">{{ $p->medication }}</h6>
                            </div>
                            <div>
                                @if($p->is_signed)
                                    <span class="status-badge bg-success-subtle text-success">Signée</span>
                                @else
                                    <span class="status-badge bg-warning-subtle text-warning">En attente</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-light rounded-3 p-2 mb-3">
                            <span class="text-muted small"><i class="fas fa-clock me-1"></i> Dosage:</span>
                            <span class="fw-bold ms-1">{{ $p->dosage ?? 'N/A' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <button class="btn-circle bg-success text-white" onclick="confirmSendPrescription({{ $p->id }})"><i class="fas fa-paper-plane fa-xs"></i></button>
                                <button class="btn-circle bg-primary text-white" onclick='editPrescription(@json($p))'><i class="fas fa-pen fa-xs"></i></button>
                                <form action="{{ route('prescriptions.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-circle bg-danger text-white btn-delete-presc"><i class="fas fa-trash-alt fa-xs"></i></button>
                                </form>
                            </div>
                            
                            @if(!$p->is_signed && auth()->id() === $p->doctor_id)
                                <form action="{{ route('prescriptions.sign', $p) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm">Signer</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="bg-white p-5 text-center rounded-4 border text-muted">Aucune prescription enregistrée.</div>
                </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="tab-coords">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Détails du Patient</h5>
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEditPatient">Modifier</button>
                </div>
                <div class="row g-4">
                    <div class="col-md-4"><p class="text-muted small mb-1">Nom</p><p class="fw-bold">{{ $patient->name }} {{ $patient->first_name }}</p></div>
                    <div class="col-md-4"><p class="text-muted small mb-1">Téléphone</p><p class="fw-bold">{{ $patient->phone }}</p></div>
                    <div class="col-md-4"><p class="text-muted small mb-1">Email</p><p class="fw-bold">{{ $patient->email }}</p></div>
                    <div class="col-12">
                        <div class="p-3 bg-danger-subtle text-danger rounded-4 fw-bold">
                            Allergies : {{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : ($patient->allergies ?? 'Néant') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddExamen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 p-4">
                <h5 class="fw-bold mb-0">Nouvel Examen Clinique</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('observations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-6"><label class="small fw-bold mb-1">Temp (°C)</label><input type="number" step="0.1" name="temperature" class="form-control rounded-3" required></div>
                        <div class="col-6"><label class="small fw-bold mb-1">Pouls (BPM)</label><input type="number" name="pulse" class="form-control rounded-3"></div>
                        <div class="col-6"><label class="small fw-bold mb-1">Poids (kg)</label><input type="number" step="0.1" name="weight" class="form-control rounded-3"></div>
                        <div class="col-6"><label class="small fw-bold mb-1">Taille (cm)</label><input type="number" name="height" class="form-control rounded-3"></div>
                        <div class="col-12"><label class="small fw-bold mb-1">Notes / Observations</label><textarea name="notes" rows="3" class="form-control rounded-3"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4">
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold py-2">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditExamen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form id="editExamenForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Modifier l'examen</h5>
                    <div class="row g-3">
                        <div class="col-6"><label class="small fw-bold mb-1">Temp</label><input type="number" step="0.1" name="temperature" id="edit_temp" class="form-control"></div>
                        <div class="col-6"><label class="small fw-bold mb-1">Pouls</label><input type="number" name="pulse" id="edit_pouls" class="form-control"></div>
                        <div class="col-6"><label class="small fw-bold mb-1">Poids</label><input type="number" step="0.1" name="weight" id="edit_weight" class="form-control"></div>
                        <div class="col-6"><label class="small fw-bold mb-1">Taille</label><input type="number" name="height" id="edit_height" class="form-control"></div>
                        <div class="col-12"><label class="small fw-bold mb-1">Notes</label><textarea name="notes" id="edit_notes" class="form-control"></textarea></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3 rounded-pill">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPrescription" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold mb-0">Modifier la prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPrescriptionForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="small fw-bold mb-1">Médicament</label><input type="text" name="medication" id="edit_med_name" class="form-control rounded-3" required></div>
                    <div class="mb-3"><label class="small fw-bold mb-1">Dosage</label><input type="text" name="dosage" id="edit_med_dosage" class="form-control rounded-3"></div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPatient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 p-4"><h5 class="fw-bold mb-0">Coordonnées</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3"><label class="small fw-bold text-muted mb-1">Téléphone</label><input type="text" name="phone" class="form-control" value="{{ $patient->phone }}"></div>
                    <div class="mb-3"><label class="small fw-bold text-muted mb-1">Email</label><input type="email" name="email" class="form-control" value="{{ $patient->email }}"></div>
                    <div class="mb-3"><label class="small fw-bold text-muted mb-1">Allergies</label><textarea name="allergies" class="form-control">{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : $patient->allergies }}</textarea></div>
                </div>
                <div class="modal-footer border-top-0 p-4"><button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Enregistrer</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJOUT DE LA FONCTION ARCHIVER
    function confirmArchive() {
        Swal.fire({
            title: 'Clôturer le dossier ?',
            text: "Le patient sera archivé et retiré de votre liste active.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, terminer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('patients.archive', $patient->id) }}";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden'; csrfToken.name = '_token'; csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden'; methodField.name = '_method'; methodField.value = 'PATCH';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function editFiche(f) {
        const form = document.getElementById('editExamenForm');
        form.action = `/observations/${f.id}`;
        document.getElementById('edit_temp').value = f.temperature;
        document.getElementById('edit_pouls').value = f.pulse;
        document.getElementById('edit_weight').value = f.weight;
        document.getElementById('edit_height').value = f.height;
        document.getElementById('edit_notes').value = f.notes;
        new bootstrap.Modal(document.getElementById('modalEditExamen')).show();
    }

    function confirmSend(id) { 
        Swal.fire({ title: 'Partager ?', text: "Envoyer à l'infirmerie ?", icon: 'question', showCancelButton: true, confirmButtonText: 'Oui', confirmButtonColor: '#1cc88a' })
        .then((r) => { if (r.isConfirmed) Swal.fire('Envoyé !', '', 'success'); }); 
    }

    function editPrescription(p) {
        const form = document.getElementById('editPrescriptionForm');
        form.action = `/prescriptions/${p.id}`;
        document.getElementById('edit_med_name').value = p.medication;
        document.getElementById('edit_med_dosage').value = p.dosage;
        new bootstrap.Modal(document.getElementById('modalEditPrescription')).show();
    }

    function confirmSendPrescription(id) {
        Swal.fire({ title: 'Envoyer ?', text: "Transmettre au patient ?", icon: 'info', showCancelButton: true, confirmButtonText: 'Oui', confirmButtonColor: '#1cc88a' })
        .then((r) => { if (r.isConfirmed) Swal.fire('Transmis !', '', 'success'); });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-trigger') || e.target.closest('.btn-delete-presc')) {
            const btn = e.target.closest('button');
            Swal.fire({ title: 'Supprimer ?', text: "Action irréversible !", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e74a3b', confirmButtonText: 'Oui' })
            .then(r => r.isConfirmed && btn.closest('form').submit());
        }
    });
</script>
</body>
</html>