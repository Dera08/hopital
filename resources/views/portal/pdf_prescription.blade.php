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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-bottom: 3px solid #667eea;
        }
        
        .hospital-info h2 {
            margin: 0 0 10px 0;
            color: #667eea;
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
            color: #667eea;
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
            color: #667eea;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .prescription-text {
            background: #fff8e1;
            padding: 20px;
            border-left: 4px solid #ffc107;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            line-height: 1.8;
            font-size: 14px;
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
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .print-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .print-btn svg {
            vertical-align: middle;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
        </svg>
        Imprimer
    </button>

    <div class="container">
        <div class="header">
            <h1>📋 ORDONNANCE MÉDICALE</h1>
        </div>

        <div class="hospital-info">
            <h2>{{ $record->hospital->name ?? 'Hôpital' }}</h2>
            <p><strong>Service:</strong> {{ $record->service->name ?? 'N/A' }}</p>
            <p><strong>Date:</strong> {{ $record->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="content">
            <div class="info-section">
                <h3>👤 Informations Patient</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Nom complet</strong>
                        {{ $record->patient_name }}
                    </div>
                    <div class="info-item">
                        <strong>IPU</strong>
                        {{ $record->patient_ipu }}
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h3>👨‍⚕️ Médecin Prescripteur</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Nom</strong>
                        Dr. {{ $record->doctor->name ?? 'Non spécifié' }}
                    </div>
                    <div class="info-item">
                        <strong>Service</strong>
                        {{ $record->service->name ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h3>💊 Prescription</h3>
                <div class="prescription-text">{{ $record->ordonnance }}</div>
            </div>

            @if(!empty($record->observations))
            <div class="info-section">
                <h3>📝 Observations</h3>
                <div class="info-item">
                    {{ $record->observations }}
                </div>
            </div>
            @endif

            <div class="signature-section">
                <p><strong>Fait le:</strong> {{ $record->created_at->format('d/m/Y') }}</p>
                <p><strong>À:</strong> {{ $record->hospital->name ?? 'N/A' }}</p>
                <div class="doctor-signature">
                    <p>Dr. {{ $record->doctor->name ?? 'Non spécifié' }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-print suggestion after page load (optional)
        window.addEventListener('load', function() {
            const shouldPrint = confirm('Voulez-vous imprimer cette ordonnance maintenant ?');
            if (shouldPrint) {
                window.print();
            }
        });
    </script>
</body>
</html>
