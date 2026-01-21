@extends('layouts.app')

@section('title', 'Tableau de Bord - Médecin Externe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-md mr-2"></i>
                        Bienvenue, Dr. {{ auth()->user()?->name ?? 'Médecin' }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_patients'] }}</h3>
                                    <p>Patients</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['total_prescriptions'] }}</h3>
                                    <p>Prescriptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-prescription"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['total_appointments'] }}</h3>
                                    <p>Rendez-vous</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> Informations</h5>
                                Votre tableau de bord de médecin externe est en cours de développement.
                                Les fonctionnalités seront bientôt disponibles.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
