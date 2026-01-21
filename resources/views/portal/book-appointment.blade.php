<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prendre un Rendez-vous</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Prendre un rendez-vous</h1>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Choisissez votre type de consultation</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="consultation-type border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 cursor-pointer transition-all hover:shadow-lg" 
                     onclick="selectConsultationType('hospital', event)">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <i class="fas fa-hospital text-blue-600 text-2xl"></i>
                        </div>
                        <input type="radio" name="consultation_type_display" value="hospital" class="w-5 h-5 text-blue-600">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">À l'hôpital</h3>
                    <p class="text-sm text-gray-600">Rendez-vous dans nos locaux avec accès à tous les équipements.</p>
                </div>

                <div class="consultation-type border-2 border-gray-200 rounded-xl p-6 hover:border-green-500 cursor-pointer transition-all hover:shadow-lg" 
                     onclick="selectConsultationType('home', event)">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-xl">
                            <i class="fas fa-home text-green-600 text-2xl"></i>
                        </div>
                        <input type="radio" name="consultation_type_display" value="home" class="w-5 h-5 text-green-600">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">À domicile</h3>
                    <p class="text-sm text-gray-600">Le médecin se déplace chez vous pour plus de confort.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('patient.book-appointment.store') }}" id="appointmentForm" style="display: none;">
            @csrf
            <input type="hidden" name="consultation_type" id="consultation_type_input">

            <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-900">Informations du rendez-vous</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Établissement *</label>
                    <select name="hospital_id" id="hospital_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Choisir un établissement</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité / Prestation *</label>
                    <select name="service_or_prestation_id" id="service_id" required disabled class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Choisir d'abord un établissement</option>
                    </select>
                    
                    <div id="price_display" class="mt-4 hidden">
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Tarif</p>
                                <p id="base_price" class="text-2xl font-bold text-blue-600">0 FCFA</p>
                            </div>
                            <div id="home_surcharge_info" class="text-right hidden">
                                <p class="text-xs text-red-600">+ 5.000 FCFA (Domicile)</p>
                                <p id="total_price_display" class="font-bold text-gray-800"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="home_address_section" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse complète *</label>
                    <textarea name="home_address" id="home_address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Heure *</label>
                        <input type="time" name="appointment_time" id="appointment_time" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif de consultation *</label>
                    <textarea name="reason" id="reason" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div id="appointment_summary" class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5">
                    <h3 class="font-bold text-gray-900 mb-2 text-sm">Résumé de votre demande</h3>
                    <div id="summary_content" class="text-sm space-y-1">
                        <p class="text-gray-500 italic">Veuillez remplir le formulaire...</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <button type="button" onclick="resetForm()" class="text-gray-600 font-medium">Retour</button>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md">
                        Confirmer le rendez-vous
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const servicesData = @json($servicesAndPrestations);
        let selectedType = '';
        let basePrice = 0;
        const surcharge = 5000;

        function selectConsultationType(type, event) {
            selectedType = type;
            document.getElementById('consultation_type_input').value = type;
            document.getElementById('appointmentForm').style.display = 'block';
            document.getElementById('home_address_section').style.display = type === 'home' ? 'block' : 'none';
            document.getElementById('home_address').required = (type === 'home');

            // Style des cartes
            document.querySelectorAll('.consultation-type').forEach(card => card.classList.replace('border-blue-500', 'border-gray-200'));
            event.currentTarget.classList.replace('border-gray-200', type === 'hospital' ? 'border-blue-500' : 'border-green-500');
            
            updatePrice();
            updateSummary();
        }

        document.getElementById('hospital_id').addEventListener('change', function() {
            const hospitalId = this.value;
            const serviceSelect = document.getElementById('service_id');
            serviceSelect.innerHTML = '<option value="">Choisir une spécialité</option>';
            
            if (servicesData[hospitalId]) {
                serviceSelect.disabled = false;
                servicesData[hospitalId].forEach(s => {
                    let opt = document.createElement('option');
                    opt.value = s.id;
                    opt.text = s.name + ' (' + s.price + ' FCFA)';
                    opt.dataset.price = s.price;
                    serviceSelect.appendChild(opt);
                });
            } else {
                serviceSelect.disabled = true;
            }
            updateSummary();
        });

        document.getElementById('service_id').addEventListener('change', updatePrice);
        ['appointment_date', 'appointment_time', 'reason', 'home_address'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateSummary);
        });

        function updatePrice() {
            const s = document.getElementById('service_id');
            const opt = s.options[s.selectedIndex];
            basePrice = (opt && opt.value) ? parseFloat(opt.dataset.price) : 0;

            if (basePrice > 0) {
                document.getElementById('price_display').classList.remove('hidden');
                document.getElementById('base_price').innerText = basePrice.toLocaleString() + ' FCFA';
                
                if (selectedType === 'home') {
                    document.getElementById('home_surcharge_info').classList.remove('hidden');
                    document.getElementById('total_price_display').innerText = 'Total: ' + (basePrice + surcharge).toLocaleString() + ' FCFA';
                } else {
                    document.getElementById('home_surcharge_info').classList.add('hidden');
                }
            }
            updateSummary();
        }

        function updateSummary() {
            const html = `
                <p><b>Type:</b> ${selectedType === 'hospital' ? 'À l\'hôpital' : 'À domicile'}</p>
                <p><b>Date:</b> ${document.getElementById('appointment_date').value || '...'} à ${document.getElementById('appointment_time').value || '...'}</p>
                <p><b>Total:</b> <span class="text-blue-600 font-bold">${(selectedType === 'home' ? basePrice + surcharge : basePrice).toLocaleString()} FCFA</span></p>
            `;
            document.getElementById('summary_content').innerHTML = html;
        }

        function resetForm() {
            location.reload();
        }
    </script>
</body>
</html>