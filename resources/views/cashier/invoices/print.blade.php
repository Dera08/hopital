<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }} - Impression</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; margin: 20px; }
        .header { border-bottom: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 30px; }
        .hospital-name { font-size: 24px; font-weight: bold; color: #1e40af; }
        .invoice-title { font-size: 20px; text-align: right; font-weight: bold; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f3f4f6; text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #f3f4f6; }
        .total-box { margin-top: 30px; text-align: right; padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #9ca3af; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
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
        <strong>Patient :</strong> {{ $invoice->patient?->name ?? 'Patient Supprimé' }}<br>
        <strong>Méthode de paiement :</strong> {{ $invoice->payment_method ?? 'Espèces' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Quantité</th>
                <th style="text-align: right;">Prix Unit.</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: right;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price, 0, ',', ' ') }} F</td>
                <td style="text-align: right;">{{ number_format($item->total, 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div><strong>Sous-total :</strong> {{ number_format($invoice->subtotal, 0, ',', ' ') }} F</div>
        <div><strong>TVA (18%) :</strong> {{ number_format($invoice->tax, 0, ',', ' ') }} F</div>
        <div style="font-size: 16px; font-weight: bold; margin-top: 10px;">
            TOTAL À PAYER : {{ number_format($invoice->total, 0, ',', ' ') }} F CFA
        </div>
    </div>

    <div class="footer">
        Document généré par HospitSIS - Logiciel de gestion hospitalière<br>
        <em>Imprimé le {{ now()->format('d/m/Y à H:i') }}</em>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Imprimer cette facture
        </button>
    </div>
</body>
</html>
