@extends('layouts.doctor')

@section('title', 'Dashboard - Dr. ' . $user->name)
@section('page-title', 'Tableau de Bord')

@push('styles')
<style>
    :root {
        --primary-color: #4facfe;
        --secondary-color: #00f2fe;
        --success-color: #43e97b;
        --warning-color: #ffa502;
        --danger-color: #ff6348;
        --info-color: #667eea;
    }

    .top-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(79, 172, 254, 0.3);
    }

    .doctor-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .doctor-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-color);
        font-weight: 700;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        opacity: 0.1;
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .stat-card.blue { border-left-color: var(--primary-color); }
    .stat-card.blue::before { background: var(--primary-color); }
    .stat-card.green { border-left-color: var(--success-color); }
    .stat-card.green::before { background: var(--success-color); }
    .stat-card.orange { border-left-color: var(--warning-color); }
    .stat-card.orange::before { background: var(--warning-color); }
    .stat-card.purple { border-left-color: var(--info-color); }
    .stat-card.purple::before { background: var(--info-color); }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-card.blue .stat-icon { background: rgba(79, 172, 254, 0.15); color: var(--primary-color); }
    .stat-card.green .stat-icon { background: rgba(67, 233, 123, 0.15); color: var(--success-color); }
    .stat-card.orange .stat-icon { background: rgba(255, 165, 2, 0.15); color: var(--warning-color); }
    .stat-card.purple .stat-icon { background: rgba(102, 126, 234, 0.15); color: var(--info-color); }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #718096;
        font-size: 0.9rem;
    }

    .stat-trend {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .content-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }

    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f3f5;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .appointment-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        transition: all 0.3s;
    }

    .appointment-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
        cursor: pointer;
    }

    .appointment-time {
        min-width: 80px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #718096;
    }

    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    .quick-action-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        width: 100%;
        justify-content: center;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        color: white;
    }

    .quick-action-btn.secondary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .quick-action-btn.success {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .quick-action-btn.warning {
        background: linear-gradient(135deg, #ffa502, #ff6348);
    }

    .patient-list-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        transition: all 0.3s;
        cursor: pointer;
    }

    .patient-list-item:hover {
        background: #e9ecef;
    }

    .patient-avatar-small {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        margin-right: 1rem;
    }

    .badge-priority-high { background: #fee; color: #c00; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    .badge-priority-medium { background: #fff3cd; color: #856404; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    .badge-priority-low { background: #d4edda; color: #155724; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <div class="top-header">
        <div class="doctor-info">
            <div class="doctor-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="mb-1">Dr. {{ $user->name }}</h2>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-stethoscope"></i> Médecin Interne
                    @if($service)
                        - Service de {{ $service->name }}
                    @endif
                </p>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar"></i> {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ $stats['total_patients'] }}</div>
            <div class="stat-label">Patients sous votre suivi</div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value">{{ $stats['appointments_today'] }}</div>
            <div class="stat-label">Consultations aujourd'hui</div>
            <div class="stat-trend text-muted">Prochain: {{ $nextAppointment }}</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-bed"></i></div>
            <div class="stat-value">{{ $stats['beds_occupied'] }}/{{ $stats['beds_total'] }}</div>
            <div class="stat-label">Lits occupés (Service)</div>
            <div class="stat-trend text-muted">Taux: {{ $stats['occupancy_rate'] }}%</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-value">{{ $stats['urgent_cases'] }}</div>
            <div class="stat-label">Cas urgents</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Rendez-vous -->
            <div class="content-card">
                <div class="card-header-custom">
                    <div class="card-title">
                        <i class="fas fa-calendar-day" style="color: var(--primary-color);"></i>
                        Vos Consultations du Jour
                    </div>
                </div>

                @forelse($todayAppointments as $appointment)
                <div class="appointment-item" onclick="window.location='{{ route('appointments.show', $appointment['id']) }}'">
                    <div class="appointment-time">
                        <i class="far fa-clock"></i> {{ $appointment['time'] }}
                    </div>
                    <div class="flex-grow-1 px-3">
                        <div><strong>{{ $appointment['patient_name'] }}</strong></div>
                        <small class="text-muted">{{ $appointment['reason'] }}</small>
                    </div>
                    <i class="fas fa-chevron-right text-muted"></i>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p class="mb-0">Aucune consultation programmée pour aujourd'hui</p>
                    <small class="text-muted">Profitez-en pour rattraper vos dossiers</small>
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
                            <span class="badge-priority-{{ $patient['priority'] }}">
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

        <div class="col-lg-4">
            <!-- Actions Rapides -->
            <div class="content-card">
                <div class="card-title mb-3">
                    <i class="fas fa-bolt" style="color: var(--warning-color);"></i>
                    Actions Rapides
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('patients.create') }}" class="quick-action-btn">
                        <i class="fas fa-user-plus"></i> Nouveau Patient
                    </a>
                    <a href="{{ route('prescriptions.create') }}" class="quick-action-btn secondary">
                        <i class="fas fa-prescription"></i> Créer Prescription
                    </a>
                    <a href="{{ route('admissions.create') }}" class="quick-action-btn success">
                        <i class="fas fa-hospital-user"></i> Admission Patient
                    </a>
                    <a href="{{ route('rooms.bed-management') }}" class="quick-action-btn warning">
                        <i class="fas fa-bed"></i> Gestion des Lits
                    </a>
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
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush
@endsection