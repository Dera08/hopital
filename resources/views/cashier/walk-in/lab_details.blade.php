<div class="space-y-4">
    <div class="border-b border-gray-200 pb-3">
        <h4 class="font-bold text-gray-800 text-lg">{{ $labRequest->patient_name }}</h4>
        <div class="flex items-center text-sm text-gray-500 mt-1">
            <span class="text-xs font-black bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-2 uppercase">Analyse Labo</span>
            <span class="text-gray-400">IPU: {{ $labRequest->patient_ipu }}</span>
        </div>
    </div>

    <div class="bg-gray-50 rounded-lg p-3">
        <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600 font-semibold">Examen: <span class="text-gray-800">{{ $labRequest->test_name }}</span></span>
        </div>
        
        <div class="mt-2 pt-2 border-t border-gray-200">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Informations Cliniques</p>
            <p class="text-sm text-gray-700 italic">
                {{ $labRequest->clinical_info ?: 'Aucune information fournie' }}
            </p>
        </div>
    </div>

    <div class="pt-2 border-t border-gray-200">
        <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
            <span>Prix Examen</span>
            <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
            <span>TVA (18%)</span>
            <span>{{ number_format($tax, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="flex justify-between items-center text-xl font-bold text-blue-800 mt-2 bg-blue-50 p-3 rounded-lg border border-blue-100">
            <span>Total à Payer</span>
            <span>{{ number_format($grandTotal, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>
</div>
