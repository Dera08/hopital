<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .logo { max-width: 150px; margin-bottom: 10px; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table td { padding: 5px; vertical-align: top; }
        table tr td:nth-child(2) { text-align: right; }
        .heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .item td { border-bottom: 1px solid #eee; }
        .total td { border-top: 2px solid #eee; font-weight: bold; }
        .status { padding: 5px 10px; color: white; border-radius: 5px; font-weight: bold; text-align: center; display: inline-block; }
        .paid { background-color: #2ecc71; }
        .unpaid { background-color: #e74c3c; }
        .print-btn { display: block; width: 100%; max-width: 200px; margin: 20px auto; padding: 10px; background: #3498db; color: white; text-align: center; text-decoration: none; border-radius: 5px; }
        @media print { .print-btn { display: none; } .invoice-box { border: none; box-shadow: none; } }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            @if($invoice->hospital && $invoice->hospital->logo)
                <img src="{{ asset('storage/' . $invoice->hospital->logo) }}" class="logo" alt="Logo">
            @else
                <h1>{{ $invoice->hospital->name ?? 'Hôpital' }}</h1>
            @endif
            <p>{{ $invoice->hospital->address ?? '' }}</p>
        </div>

        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Facture #:</strong> {{ $invoice->invoice_number }}<br>
                                <strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}<br>
                                <strong>Date d'échéance:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                <strong>Patient:</strong><br>
                                {{ $patient->name }} {{ $patient->first_name }}<br>
                                IPU: {{ $patient->ipu }}<br>
                                {{ $patient->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Description</td>
                <td>Montant</td>
            </tr>

            @foreach($invoice->items as $item)
            <tr class="item">
                <td>{{ $item->description }}</td>
                <td>{{ number_format($item->total, 2) }} €</td> <!-- Assuming currency, replace if needed -->
            </tr>
            @endforeach

            <tr class="total">
                <td></td>
                <td>Total: {{ number_format($invoice->total, 2) }} €</td>
            </tr>
        </table>

        <br>
        <div style="text-align: center;">
            <span class="status {{ $invoice->status === 'paid' ? 'paid' : 'unpaid' }}">
                {{ $invoice->status === 'paid' ? 'PAYÉE le ' . ($invoice->paid_at ? $invoice->paid_at->format('d/m/Y') : '') : 'NON PAYÉE' }}
            </span>
        </div>
    </div>

    <a href="javascript:window.print()" class="print-btn">Imprimer / Télécharger PDF</a>
</body>
</html>
