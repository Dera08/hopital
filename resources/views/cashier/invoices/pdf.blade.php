<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; }
        .header { border-bottom: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 30px; }
        .hospital-name { font-size: 24px; font-weight: bold; color: #1e40af; }
        .invoice-title { font-size: 20px; text-align: right; font-weight: bold; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f3f4f6; text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #f3f4f6; }
        .total-box { margin-top: 30px; text-align: right; padding: 20px; background: #f9fafb; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <table style="border: none;">
            <tr>
                <td style="border: none; padding: 0;">
                    <div class="hospital-name">{{ $invoice->hospital->name ?? 'HospitSIS' }}</div>
                    <div>{{ $invoice->hospital->address ?? 'Adresse du centre' }}</div>
                </td>
                <td style="border: none; padding: 0;" class="invoice-title">
                    FACTURE<br>
                    <span style="font-size: 14px; color: #6b7280;">#{{ $invoice->invoice_number }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <strong>Date :</strong> {{ $invoice->invoice_date->format('d/m/Y') }}<br>
        <strong>Patient :</strong> {{ $invoice->patient?->name ?? 'Patient Supprimé' }}
        @if($invoice->insurance_coverage_rate > 0)
            <br><strong>Assurance :</strong> {{ $invoice->insurance_name ?? $invoice->payment_operator }} ({{ $invoice->insurance_coverage_rate }}%)
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @php $appointment = $invoice->admission?->appointment; @endphp
            <tr>
                <td>{{ $appointment?->service?->name ?? 'Consultation' }}</td>
                <td style="text-align: right;">{{ number_format($appointment?->service?->price ?? 0, 0, ',', ' ') }} F</td>
            </tr>
            @foreach($appointment?->prestations ?? [] as $prestation)
            <tr>
                <td style="color: #6b7280;">+ {{ $prestation->name }}</td>
                <td style="text-align: right;">{{ number_format($prestation->pivot->total, 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div style="margin-bottom: 5px;">
            <span style="font-size: 14px; font-weight: bold;">TOTAL FACTURÉ :</span>
            <span style="font-size: 14px; font-weight: bold;">{{ number_format($invoice->total, 0, ',', ' ') }} F</span>
        </div>
        
        @if($invoice->insurance_coverage_rate > 0)
            @php
                $insurancePart = ($invoice->total * $invoice->insurance_coverage_rate) / 100;
                $patientPart = $invoice->total - $insurancePart;
            @endphp
            <div style="color: #7c3aed; font-size: 12px; margin-bottom: 5px;">
                Portion Assurance ({{ $invoice->insurance_coverage_rate }}%) : - {{ number_format($insurancePart, 0, ',', ' ') }} F
            </div>
            <div style="border-top: 1px solid #059669; padding-top: 5px;">
                <span style="font-size: 16px; font-weight: bold; color: #059669;">TOTAL ENCAISSÉ (PATIENT) :</span>
                <span style="font-size: 20px; font-weight: bold; color: #059669;">{{ number_format($patientPart, 0, ',', ' ') }} F CFA</span>
            </div>
        @else
            <span style="font-size: 16px; font-weight: bold;">TOTAL À PAYER :</span>
            <span style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ number_format($invoice->total, 0, ',', ' ') }} F CFA</span>
        @endif
    </div>

    <div class="footer">
        Document généré par HospitSIS - Logiciel de gestion hospitalière
    </div>
</body>
</html>