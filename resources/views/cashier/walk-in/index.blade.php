@extends('layouts.cashier_layout')

@section('title', 'Consultations Sans Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Consultations Sans Rendez-vous</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createWalkInModal">
                            <i class="fas fa-plus"></i> Nouvelle Consultation
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($walkInConsultations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Téléphone</th>
                                        <th>Service</th>
                                        <th>Date/Heure</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($walkInConsultations as $consultation)
                                        <tr>
                                            <td>{{ $consultation->id }}</td>
                                            <td>{{ $consultation->patient->name }}</td>
                                            <td>{{ $consultation->patient->phone }}</td>
                                            <td>{{ $consultation->service->name }}</td>
                                            <td>{{ $consultation->consultation_datetime->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @php
                                                    $servicePrice = $consultation->service->price ?? 0;
                                                    $prestationsTotal = $consultation->prestations->sum('pivot.total');
                                                    $total = $servicePrice + $prestationsTotal;
                                                @endphp
                                                {{ number_format($total, 2) }} €
                                            </td>
                                            <td>
                                                @if($consultation->status === 'pending_payment')
                                                    <span class="badge badge-warning">En attente de paiement</span>
                                                @elseif($consultation->status === 'paid')
                                                    <span class="badge badge-success">Payé</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($consultation->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($consultation->status === 'pending_payment')
                                                    <button type="button" class="btn btn-sm btn-success validate-payment-btn"
                                                            data-id="{{ $consultation->id }}"
                                                            data-toggle="modal"
                                                            data-target="#validatePaymentModal">
                                                        <i class="fas fa-check"></i> Valider Paiement
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $walkInConsultations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune consultation sans rendez-vous trouvée</h4>
                            <p class="text-muted">Les nouvelles consultations apparaîtront ici.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Créer Consultation Sans RDV -->
<div class="modal fade" id="createWalkInModal" tabindex="-1" role="dialog" aria-labelledby="createWalkInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWalkInModalLabel">Nouvelle Consultation Sans Rendez-vous</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createWalkInForm" action="{{ route('cashier.walk-in.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patient_name">Nom du Patient</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patient_phone">Téléphone</label>
                                <input type="text" class="form-control" id="patient_phone" name="patient_phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patient_email">Email (optionnel)</label>
                                <input type="email" class="form-control" id="patient_email" name="patient_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_id">Service</label>
                                <select class="form-control" id="service_id" name="service_id" required>
                                    <option value="">Sélectionner un service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }} - {{ number_format($service->price, 2) }} €</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Prestations Additionnelles (optionnel)</label>
                        <div class="row">
                            @foreach($prestations as $prestation)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="prestation_ids[]" value="{{ $prestation->id }}" id="prestation_{{ $prestation->id }}">
                                        <label class="form-check-label" for="prestation_{{ $prestation->id }}">
                                            {{ $prestation->name }} - {{ number_format($prestation->price, 2) }} €
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer Consultation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Validation Paiement -->
<div class="modal fade" id="validatePaymentModal" tabindex="-1" role="dialog" aria-labelledby="validatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validatePaymentModalLabel">Valider le Paiement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="validatePaymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="paymentDetails">
                        <!-- Les détails seront chargés dynamiquement -->
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Méthode de Paiement</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="Espèces">Espèces</option>
                            <option value="Carte bancaire">Carte bancaire</option>
                            <option value="Virement">Virement</option>
                            <option value="Chèque">Chèque</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider le Paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Gestionnaire pour le bouton de validation de paiement
    $('.validate-payment-btn').on('click', function() {
        var consultationId = $(this).data('id');

        // Charger les détails de la consultation
        $.get('/cashier/walk-in/' + consultationId + '/details', function(data) {
            $('#paymentDetails').html(data);
            $('#validatePaymentForm').attr('action', '/cashier/walk-in/' + consultationId + '/validate-payment');
        });
    });
});
</script>
@endsection
