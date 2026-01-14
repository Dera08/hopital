@extends('layouts.app')

@section('title', 'Dashboard Médecin Interne')

@section('content')
<style>
    /* Tous les styles CSS de l'artifact HTML précédent vont ici */
    /* Je les ai omis pour la brièveté, mais copiez-les depuis l'artifact "Dashboard Médecin Interne Amélioré" */
</style>

<div class="container-fluid p-4">
    <!-- En-tête Principal -->
    <div class="top-header">
        <div class="doctor-info">
            <div class="doctor-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="mb-1">Dr. {{ $user->name }}</h2>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-stethoscope"></i> Médecin Interne - Service de {{ $service->name ?? 'N/A' }}
                </p>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar"></i> {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $stats['total_patients'] }}</div>
            <div class="stat-label">Patients sous votre suivi</div>
            <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up"></i> +3 cette semaine
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-value">{{ $stats['appointments_today'] }}</div>
            <div class="stat-label">Consultations aujourd'hui</div>
            <div class="stat-trend">
                Prochain: {{ $nextAppointment }}
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-value">{{ $stats['beds_occupied'] }}/{{ $stats['beds_total'] }}</div>
            <div class="stat-label">Lits occupés (Service)</div>
            <div class="stat-trend">
                Taux: {{ $stats['occupancy_rate'] }}%
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $stats['urgent_cases'] }}</div>
            <div class="stat-label">Cas urgents</div>
            <div class="stat-trend trend-down">
                <i class="fas fa-arrow-down"></i> Sous contrôle
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne Gauche -->
        <div class="col-lg-8">
            <!-- Rendez-vous du Jour -->
            <div class="content-card">
                <div class="card-header-custom">
                    <div class="card-title">
                        <i class="fas fa-calendar-day" style="color: var(--primary-color);"></i>
                        Vos Consultations du Jour
                    </div>
                </div>

                @forelse($todayAppointments as $appointment)
                <div class="appointment-item">
                    <div class="appointment-time">
                        <i class="far fa-clock"></i> {{ $appointment['time'] }}
                    </div>
                    <div class="appointment-details">
                        <div class="appointment-patient">
                            <i class="fas fa-user"></i> {{ $appointment['patient_name'] }}
                        </div>
                        <div class="appointment-reason">
                            {{ $appointment['reason'] ?? 'Consultation générale' }}
                        </div>
                    </div>
                    <span class="appointment-status status-{{ $appointment['status'] }}">
                        {{ ucfirst($appointment['status']) }}
                    </span>
                    <a href="{{ route('appointments.show', $appointment['id']) }}" class="btn btn-sm btn-outline-primary ms-2">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p class="mb-0">Aucune consultation programmée pour aujourd'hui</p>
                    <small class="text-muted">Profitez-en pour rattraper vos dossiers administratifs</small>
                </div>
                @endforelse
            </div>

            <!-- Patients Prioritaires -->
            <div class="content-card">
                <div class="card-header-custom">
                    <div class="card-title">
                        <i class="fas fa-user-injured" style="color: var(--danger-color);"></i>
                        Patients Nécessitant une Attention Prioritaire
                    </div>
                </div>

                @forelse($priorityPatients as $patient)
                <div class="patient-list-item" onclick="window.location='{{ route('patients.show', $patient['id']) }}'">
                    <div class="patient-avatar-small">
                        {{ strtoupper(substr($patient['name'], 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $patient['name'] }}</strong>
                                <div class="text-muted small">
                                    <i class="fas fa-procedures"></i> Chambre {{ $patient['room'] }}
                                </div>
                            </div>
                            <span class="badge-custom badge-priority-{{ $patient['priority'] }}">
                                {{ $patient['priority_reason'] }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p class="mb-0">Aucun patient en situation prioritaire</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Colonne Droite -->
        <div class="col-lg-4">
            <!-- Actions Rapides -->
            <div class="content-card">
                <div class="card-title mb-3">
                    <i class="fas fa-bolt" style="color: var(--warning-color);"></i>
                    Actions Rapides
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('patients.create') }}" class="quick-action-btn">
                        <i class="fas fa-user-plus"></i>
                        Nouveau Patient
                    </a>
                    <a href="{{ route('prescriptions.create') }}" class="quick-action-btn secondary">
                        <i class="fas fa-prescription"></i>
                        Créer une Prescription
                    </a>
                    <a href="{{ route('admissions.create') }}" class="quick-action-btn success">
                        <i class="fas fa-hospital-user"></i>
                        Admission Patient
                    </a>
                    <a href="{{ route('rooms.bed-management') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #ffa502, #ff6348);">
                        <i class="fas fa-bed"></i>
                        Gestion des Lits
                    </a>
                </div>
            </div>

            <!-- Activités Récentes -->
            <div class="content-card">
                <div class="card-title mb-3">
                    <i class="fas fa-history" style="color: var(--info-color);"></i>
                    Activités Récentes
                </div>
                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-time">Il y a 15 min</div>
                        <div class="activity-content">
                            Consultation terminée - <strong>Patient</strong>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">Il y a 1h</div>
                        <div class="activity-content">
                            Prescription créée
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">Il y a 2h</div>
                        <div class="activity-content">
                            Admission patient
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicateurs de Performance -->
            <div class="content-card">
                <div class="card-title mb-3">
                    <i class="fas fa-chart-line" style="color: var(--success-color);"></i>
                    Performance du Service
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Satisfaction Patients</small>
                        <small class="text-muted">94%</small>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-custom" style="width: 94%; background: linear-gradient(90deg, var(--success-color), var(--primary-color));"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Respect des Délais</small>
                        <small class="text-muted">88%</small>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-custom" style="width: 88%; background: linear-gradient(90deg, var(--info-color), var(--primary-color));"></div>
                    </div>
                </div>

                <div class="mb-0">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Dossiers Complets</small>
                        <small class="text-muted">76%</small>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-custom" style="width: 76%; background: linear-gradient(90deg, var(--warning-color), var(--danger-color));"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });
    });
</script>
@endpush
@endsection