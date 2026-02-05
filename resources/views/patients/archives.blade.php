<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive - {{ $patient->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { --archive-primary: #64748b; --bg-archive: #f1f5f9; }
        body { background-color: var(--bg-archive); font-family: 'Inter', sans-serif; }
        .top-banner { background: linear-gradient(135deg, #64748b 0%, #475569 100%); height: 140px; border-radius: 0 0 30px 30px; }
        .main-card { background: white; border-radius: 20px; margin-top: -60px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 25px; border: 2px solid #e2e8f0; }
        .archive-badge { background: #f1f5f9; color: #64748b; font-weight: 900; padding: 8px 20px; border-radius: 12px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; border: 2px solid #cbd5e1; }
        .timeline-item { background: #f8fafc; border-radius: 18px; border: 1px solid #e2e8f0; margin-bottom: 20px; padding: 20px; position: relative; }
        .timeline-item::before { content: ''; position: absolute; left: -30px; top: 30px; width: 20px; height: 20px; background: #94a3b8; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 0 3px #e2e8f0; }
        .read-only-label { background: #fee2e2; color: #dc2626; font-weight: 800; padding: 6px 14px; border-radius: 10px; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.2px; }
        .data-card { background: white; border-radius: 15px; border: 1px solid #e2e8f0; padding: 18px; margin-bottom: 15px; }
        .section-title { color: #1e293b; font-weight: 800; font-size: 1.1rem; margin-bottom: 15px; border-left: 4px solid var(--archive-primary); padding-left: 12px; }
    </style>
</head>
<body>

<div class="top-banner p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-white mb-0"><i class="fas fa-archive me-2"></i>HospitSIS <span class="fw-light text-white-50">Archives</span></h5>
        <a href="{{ route('medical_records.archives') }}" class="btn btn-sm btn-light rounded-pill px-4 fw-bold">← Retour aux Archives</a>
    </div>
</div>

<div class="container mb-5">
    <div class="main-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-auto text-center">
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:70px; height:70px; font-size:24px; font-weight:bold; border: 3px solid #cbd5e1;">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}{{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
            </div>
            <div class="col-md text-center text-md-start">
                <h2 class="fw-bold mb-1">{{ $patient->name }} {{ $patient->first_name }}</h2>
                <div class="text-muted small mb-2">IPU: {{ $patient->ipu }} • {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : '?' }} ans</div>
                <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                    <span class="archive-badge"><i class="fas fa-archive me-1"></i>DOSSIER ARCHIVÉ</span>
                    <span class="read-only-label"><i class="fas fa-lock me-1"></i>LECTURE SEULE</span>
                </div>
            </div>
            <div class="col-md-auto mt-3 mt-md-0">
                <div class="text-center">
                    <div class="text-muted small">Période archivée</div>
                    <div class="fw-bold text-secondary">{{ $archivedVitals->min('created_at')->format('d/m/Y') }} - {{ $archivedVitals->max('created_at')->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- PARCOURS COMPLET -->
    <div class="row">
        <div class="col-lg-8">
            <h4 class="section-title"><i class="fas fa-heartbeat me-2"></i>Parcours de Soins Complet</h4>
            
            <div style="position: relative; padding-left: 40px; border-left: 3px solid #e2e8f0; margin-left: 20px;">
                @forelse($allExams as $exam)
                    @php
                        $isClinical = $exam instanceof \App\Models\ClinicalObservation;
                        $examType = $isClinical ? $exam->type : 'consultation';
                    @endphp
                    
                    <div class="timeline-item">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold small">
                                    {{ \Carbon\Carbon::parse($exam->observation_datetime ?? $exam->created_at)->format('d/m/Y à H:i') }}
                                </span>
                                <span class="badge {{ $examType === 'detailed' ? 'bg-success' : 'bg-info' }} bg-opacity-10 rounded-pill px-3 py-2 fw-bold small ms-2">
                                    @if($examType === 'detailed') <i class="fas fa-file-medical me-1"></i>Examen Clinique 
                                    @else <i class="fas fa-notes-medical me-1"></i>Consultation @endif
                                </span>
                            </div>
                        </div>
                        
                        @if($examType !== 'detailed')
                            <div class="row g-0 text-center border rounded-3 bg-white mb-3 overflow-hidden p-2">
                                <div class="col border-end">
                                    <div class="small text-muted fw-bold">TEMP</div>
                                    <div class="fw-bold">{{ $exam->temperature ?? '--' }}°C</div>
                                </div>
                                <div class="col border-end">
                                    <div class="small text-muted fw-bold">POULS</div>
                                    <div class="fw-bold">{{ $exam->pulse ?? '--' }}</div>
                                </div>
                                <div class="col border-end">
                                    <div class="small text-muted fw-bold">POIDS</div>
                                    <div class="fw-bold">{{ $exam->weight ?? '--' }}kg</div>
                                </div>
                                <div class="col">
                                    <div class="small text-muted fw-bold">AGENT</div>
                                    <div class="fw-bold small">{{ $exam->user->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-light rounded-3 p-3 small">
                            <strong class="text-secondary">Notes :</strong>
                            <div class="mt-1">{{ $exam->notes ?? $exam->reason ?? 'Aucune note.' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">Aucun examen enregistré</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <!-- PRESCRIPTIONS -->
            <h5 class="section-title"><i class="fas fa-prescription me-2"></i>Prescriptions</h5>
            @forelse($patient->prescriptions as $p)
                <div class="data-card">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <div class="fw-bold">{{ $p->medication }}</div>
                            <div class="small text-muted">{{ $p->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="badge {{ $p->is_signed ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $p->is_signed ? 'success' : 'warning' }} rounded-pill px-2 py-1 small fw-bold">
                            {{ $p->is_signed ? 'Signée' : 'En attente' }}
                        </span>
                    </div>
                    <div class="small text-secondary"><strong>Dosage :</strong> {{ $p->dosage ?? 'N/A' }}</div>
                    <div class="small text-muted mt-1">Dr. {{ $p->doctor->name ?? 'N/A' }}</div>
                </div>
            @empty
                <div class="text-center text-muted py-3 small">Aucune prescription</div>
            @endforelse

            <!-- ANALYSES -->
            <h5 class="section-title mt-4"><i class="fas fa-vial me-2"></i>Analyses & Imagerie</h5>
            @forelse($patient->labRequests as $req)
                <div class="data-card">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <div class="fw-bold">{{ $req->test_name }}</div>
                            <div class="small text-muted">{{ $req->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="badge bg-{{ $req->status === 'completed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $req->status === 'completed' ? 'success' : 'warning' }} rounded-pill px-2 py-1 small fw-bold">
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>
                    @if($req->clinical_info)
                        <div class="small text-secondary fst-italic">{{ $req->clinical_info }}</div>
                    @endif
                    <div class="small text-muted mt-1">Dr. {{ $req->doctor->name ?? 'N/A' }}</div>
                </div>
            @empty
                <div class="text-center text-muted py-3 small">Aucune analyse</div>
            @endforelse

            <!-- INFO PATIENT -->
            <div class="data-card mt-4 bg-danger bg-opacity-10 border-danger">
                <h6 class="fw-bold text-danger mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Allergies</h6>
                <div class="small">{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : ($patient->allergies ?? 'Néant') }}</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
