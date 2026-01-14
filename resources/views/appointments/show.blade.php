@extends('layouts.app')

@section('title', 'Rendez-vous - Détails')

@section('content')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Détails du Rendez-vous</h2>
                    <div>
                        {{-- Bouton pour modifier le rendez-vous --}}
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        {{-- Bouton pour retourner à la liste --}}
                        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Rendez-vous n°{{ $appointment->id }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">Informations Générales</h5>
                        <p><strong>Patient :</strong> <a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->full_name }} ({{ $appointment->patient->ipu }})</a></p>
                        <p><strong>Médecin :</strong> {{ $appointment->doctor->name }}</p>
                        <p><strong>Service :</strong> {{ $appointment->service->name }}</p>
                        <p><strong>Statut :</strong> 
                            <span class="badge {{ match($appointment->status) {
                                'pending' => 'bg-warning',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                default => 'bg-secondary',
                            } }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 mb-3">Date & Heure</h5>
                        <p>
                            <strong>Date et Heure :</strong> 
                            {{ $appointment->appointment_datetime->format('d/m/Y à H:i') }}
                        </p>
                        <p><strong>Durée Estimée :</strong> {{ $appointment->duration_minutes }} minutes</p>
                        <p><strong>Lieu :</strong> {{ $appointment->location ?? 'Non spécifié' }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5 class="border-bottom pb-2 mb-3">Motif de Consultation</h5>
                    <p>{{ $appointment->reason ?? 'Aucun motif détaillé.' }}</p>
                </div>

                {{-- Formulaire de suppression --}}
                <div class="mt-4 pt-3 border-top">
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler/supprimer ce rendez-vous ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Supprimer/Annuler
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection