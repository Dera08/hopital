<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ordonnance Médicale</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.6; color: #333; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header {
            background: @if($prescription->category === 'nurse') linear-gradient(135deg, #6366f1 0%, #4338ca 100%) @else linear-gradient(135deg, #ec4899 0%, #be185d 100%) @endif;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }
        
        .hospital-info {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 3px solid @if($prescription->category === 'nurse') #6366f1 @else #ec4899 @endif;
        }
        
        .hospital-info h2 {
            margin: 0 0 10px 0;
            color: @if($prescription->category === 'nurse') #4338ca @else #be185d @endif;
            font-size: 20px;
        }
        
        .hospital-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .content {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .info-section:last-child {
            border-bottom: none;
        }
        
        .info-section h3 {
            color: #be185d;
            margin: 0 0 15px 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-item strong {
            color: #be185d;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .prescription-box {
            background: #fffafa;
            padding: 25px;
            border-radius: 15px;
            border: 2px solid #fce7f3;
            margin-top: 10px;
        }
        
        .medication-name {
            font-size: 22px;
            font-weight: 900;
            color: #111827;
            margin-bottom: 10px;
            border-bottom: 2px solid #ec4899;
            display: inline-block;
            padding-bottom: 4px;
        }
        
        .prescription-details {
            margin-top: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .detail-item {
            background: white;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #f3f4f6;
        }
        
        .detail-item span {
            display: block;
            font-size: 10px;
            font-weight: 800;
            color: #ec4899;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .detail-item p {
            margin: 0;
            font-weight: 600;
            color: #374151;
        }

        .instructions-box {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #ec4899;
            font-style: italic;
            color: #4b5563;
        }
        
        .signature-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
            text-align: right;
        }
        
        .signature-section p {
            margin: 5px 0;
            color: #666;
        }
        
        .doctor-signature {
            margin-top: 50px;
            border-top: 2px solid #333;
            display: inline-block;
            padding-top: 5px;
            min-width: 250px;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ec4899;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .print-btn:hover {
            background: #be185d;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">Imprimer Ordonnance</button>

    <div class="container">
        <div class="header">
            @if($prescription->category === 'nurse')
                <h1>📋 CONSIGNE DE SOINS</h1>
            @else
                <h1>💊 ORDONNANCE PHARMACIE</h1>
            @endif
        </div>

        <div class="hospital-info">
            <h2>{{ $prescription->hospital->name ?? 'Établissement de Santé' }}</h2>
            <p><strong>Réf:</strong> #ORD-{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Date de prescription:</strong> {{ $prescription->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="content">
            <div class="info-section">
                <h3>👤 Patient</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Nom du patient</strong>
                        {{ $prescription->patient->name ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <strong>Identifiant (IPU)</strong>
                        {{ $prescription->patient->ipu ?? 'N/A' }}
                    </div>
                </div>

                <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <strong class="text-[10px] text-gray-500 uppercase font-black block mb-1">Motif de consultation</strong>
                    <p class="text-sm font-semibold text-gray-800">{{ $motif }}</p>
                </div>
            </div>

            <div class="info-section">
                <h3>🩺 Prescription Médicale</h3>
                <div class="prescription-box">
                    <div class="medication-name">{{ $prescription->medication }}</div>
                    
                    <div class="prescription-details">
                        @if($prescription->dosage)
                        <div class="detail-item">
                            <span>Posologie / Dosage</span>
                            <p>{{ $prescription->dosage }}</p>
                        </div>
                        @endif
                        
                        @if($prescription->frequency)
                        <div class="detail-item">
                            <span>Fréquence / Prise</span>
                            <p>{{ $prescription->frequency }}</p>
                        </div>
                        @endif

                        @if($prescription->start_date)
                        <div class="detail-item">
                            <span>Date Début</span>
                            <p>{{ $prescription->start_date->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        @if($prescription->end_date)
                        <div class="detail-item">
                            <span>Date Fin</span>
                            <p>{{ $prescription->end_date->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($prescription->instructions)
                    <div class="instructions-box">
                        <strong>Instructions complémentaires :</strong><br>
                        {{ $prescription->instructions }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="signature-section">
                <p><strong>Fait à:</strong> {{ $prescription->hospital->city ?? 'Abidjan' }}</p>
                <p><strong>Le:</strong> {{ $prescription->created_at->format('d/m/Y') }}</p>
                <div class="doctor-signature">
                    <p>Dr. {{ $prescription->doctor->name ?? 'Praticien' }}</p>
                    <p class="small" style="font-size: 10px; opacity: 0.5;">Validé numériquement</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                if (confirm('Voulez-vous imprimer cette ordonnance ?')) {
                    window.print();
                }
            }, 500);
        });
    </script>
</body>
</html>
