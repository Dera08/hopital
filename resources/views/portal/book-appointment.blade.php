<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un Rendez-vous</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('patient.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Prendre un rendez-vous</h1>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Choix du Type de Consultation -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Choisissez votre type de consultation</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Consultation à l'hôpital -->
                <div class="consultation-type border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 cursor-pointer transition-all hover:shadow-lg" 
                     onclick="selectConsultationType('hospital')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <i class="fas fa-hospital text-blue-600 text-2xl"></i>
                        </div>
                        <input type="radio" name="consultation_type" value="hospital" class="w-5 h-5 text-blue-600">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">À l'hôpital</h3>
                    <p class="text-sm text-gray-600">
                        Rendez-vous dans nos locaux avec accès à tous les équipements médicaux nécessaires.
                    </p>
                    <div class="mt-4 space-y-2">
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Équipement médical complet
                        </p>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Examens sur place
                        </p>
                    </div>
                </div>

                <!-- Visite à domicile -->
                <div class="consultation-type border-2 border-gray-200 rounded-xl p-6 hover:border-green-500 cursor-pointer transition-all hover:shadow-lg" 
                     onclick="selectConsultationType('home')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-xl">
                            <i class="fas fa-home text-green-600 text-2xl"></i>
                        </div>
                        <input type="radio" name="consultation_type" value="home" class="w-5 h-5 text-green-600">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">À domicile</h3>
                    <p class="text-sm text-gray-600">
                        Le médecin se déplace chez vous pour plus de confort et de praticité.
                    </p>
                    <div class="mt-4 space-y-2">
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Confort de votre domicile
                        </p>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Pas de déplacement
                        </p>
                        <p class="text-xs text-red-500 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Supplément appliqué
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Formulaire de Rendez-vous -->
        <form method="POST" action="{{ route('patient.book-appointment.store') }}" id="appointmentForm" style="display: none;">
            @csrf
            
            <input type="hidden" name="consultation_type" id="consultation_type_input">

            <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                
                <h2 class="text-xl font-bold text-gray-900">Informations du rendez-vous</h2>

                <!-- Sélection de l'Hôpital -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hospital mr-2"></i>Établissement *
                    </label>
                    <select 
                        name="hospital_id" 
                        id="hospital_id"
                        required
                        onchange="loadServices()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Choisir un établissement</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}" {{ $patient->hospital_id == $hospital->id ? 'selected' : '' }}>
                                {{ $hospital->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Les tarifs varient selon l'établissement choisi
                    </p>
                </div>

                <!-- Date et Heure -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Date souhaitée *
                        </label>
                        <input 
                            type="date" 
                            name="appointment_date" 
                            id="appointment_date"
                            required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            onchange="updateSummary()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2"></i>Heure souhaitée *
                        </label>
                        <select 
                            name="appointment_time" 
                            id="appointment_time"
                            required
                            onchange="updateSummary()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Choisir une heure</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                </div>

                <!-- Service / Spécialité avec PRIX -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-stethoscope mr-2"></i>Spécialité / Service *
                    </label>
                    <select 
                        name="service_id" 
                        id="service_id"
                        required
                        onchange="updatePrice()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled
                    >
                        <option value="">Choisir d'abord un établissement</option>
                    </select>
                    
                    <!-- Affichage dynamique du PRIX -->
                    <div id="price_display" class="mt-4 hidden">
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tarif de consultation</p>
                                    <p id="base_price" class="text-2xl font-bold text-blue-600 mt-1">0 FCFA</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div id="home_surcharge" class="mt-3 pt-3 border-t border-blue-200 hidden">
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-plus-circle mr-1"></i>
                                    Supplément visite à domicile : <span id="surcharge_amount" class="font-semibold">+5.000 FCFA</span>
                                </p>
                                <p id="total_price" class="text-lg font-bold text-gray-800 mt-2">
                                    Total : <span class="text-green-600">0 FCFA</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse (si visite à domicile) -->
                <div id="home_address_section" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Adresse complète *
                    </label>
                    <textarea 
                        name="home_address" 
                        id="home_address"
                        rows="3"
                        placeholder="Entrez votre adresse complète (rue, quartier, commune, ville)"
                        onchange="updateSummary()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    ></textarea>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Cette adresse sera communiquée au médecin pour la visite à domicile.
                    </p>
                </div>

                <!-- Motif de consultation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-medical mr-2"></i>Motif de consultation *
                    </label>
                    <textarea 
                        name="reason" 
                        id="reason"
                        required
                        rows="4"
                        placeholder="Décrivez brièvement votre motif de consultation..."
                        onchange="updateSummary()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>

                <!-- Notes additionnelles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-notes-medical mr-2"></i>Notes additionnelles (optionnel)
                    </label>
                    <textarea 
                        name="notes" 
                        id="notes"
                        rows="3"
                        placeholder="Allergies, médicaments en cours, informations importantes..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>

                <!-- Résumé DYNAMIQUE du RDV -->
                <div id="appointment_summary" class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>
                        Résumé de votre demande
                    </h3>
                    <div id="summary_content" class="space-y-2 text-sm">
                        <p class="text-gray-500 italic">Remplissez le formulaire pour voir le résumé...</p>
                    </div>
                </div>

                <!-- Avertissement Paiement -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Information importante</h4>
                            <p class="text-sm text-gray-700">
                                Une facture sera générée après confirmation. Vous devez <strong>régler le montant à la caisse</strong> avant votre consultation.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-between pt-4">
                    <button 
                        type="button"
                        onclick="resetForm()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </button>
                    <button 
                        type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-md"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>Confirmer le rendez-vous
                    </button>
                </div>

            </div>
        </form>

    </main>

    <script>
        // Passer les données des services depuis PHP vers JavaScript
        const servicesData = @json($services);

        // Debug: Afficher les données chargées
        console.log('Services data loaded:', servicesData);

        // Charger les services au chargement de la page si un hôpital est déjà sélectionné
        document.addEventListener('DOMContentLoaded', function() {
            const hospitalSelect = document.getElementById('hospital_id');
            console.log('Hospital select value on load:', hospitalSelect.value);
            if (hospitalSelect.value) {
                loadServices();
            }
        });
        
        let selectedType = '';
        let basePrice = 0;
        const homeSurcharge = 5000; // Supplément visite à domicile

        function selectConsultationType(type) {
            selectedType = type;
            document.getElementById('consultation_type_input').value = type;
            document.getElementById('appointmentForm').style.display = 'block';
            
            const homeAddressSection = document.getElementById('home_address_section');
            const homeAddressInput = document.querySelector('textarea[name="home_address"]');
            const homeSurchargeDiv = document.getElementById('home_surcharge');
            
            if (type === 'home') {
                homeAddressSection.style.display = 'block';
                homeAddressInput.required = true;
                if (basePrice > 0) {
                    homeSurchargeDiv.classList.remove('hidden');
                    updateTotalPrice();
                }
            } else {
                homeAddressSection.style.display = 'none';
                homeAddressInput.required = false;
                homeSurchargeDiv.classList.add('hidden');
            }
            
            document.querySelectorAll('.consultation-type').forEach(card => {
                card.classList.remove('border-blue-500', 'border-green-500', 'shadow-lg');
                card.classList.add('border-gray-200');
            });
            
            const selectedCard = event.currentTarget;
            selectedCard.classList.remove('border-gray-200');
            selectedCard.classList.add(type === 'hospital' ? 'border-blue-500' : 'border-green-500', 'shadow-lg');
            selectedCard.querySelector('input[type="radio"]').checked = true;
            
            setTimeout(() => {
                document.getElementById('appointmentForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
            
            updateSummary();
        }
        
        function loadServices() {
            const hospitalId = document.getElementById('hospital_id').value;
            const serviceSelect = document.getElementById('service_id');

            console.log('loadServices called with hospitalId:', hospitalId);
            console.log('servicesData keys:', Object.keys(servicesData));
            console.log('servicesData[hospitalId]:', servicesData[hospitalId]);

            serviceSelect.innerHTML = '<option value="">Chargement...</option>';
            serviceSelect.disabled = true;

            if (!hospitalId) {
                serviceSelect.innerHTML = '<option value="">Choisir d\'abord un établissement</option>';
                document.getElementById('price_display').classList.add('hidden');
                return;
            }

            const services = servicesData[hospitalId] || [];

            console.log('Services found for hospital', hospitalId, ':', services);

            serviceSelect.innerHTML = '<option value="">Choisir une spécialité</option>';
            services.forEach(service => {
                console.log('Adding service:', service);
                const option = document.createElement('option');
                option.value = service.id;
                option.textContent = `${service.name} - ${formatPrice(service.price)} FCFA`;
                option.setAttribute('data-price', service.price);
                serviceSelect.appendChild(option);
            });

            serviceSelect.disabled = false;
            updateSummary();
        }
        
        function updatePrice() {
            const serviceSelect = document.getElementById('service_id');
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                document.getElementById('price_display').classList.add('hidden');
                basePrice = 0;
                updateSummary();
                return;
            }
            
            basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            
            document.getElementById('base_price').textContent = formatPrice(basePrice) + ' FCFA';
            document.getElementById('price_display').classList.remove('hidden');
            
            if (selectedType === 'home') {
                document.getElementById('home_surcharge').classList.remove('hidden');
                document.getElementById('surcharge_amount').textContent = '+' + formatPrice(homeSurcharge) + ' FCFA';
                updateTotalPrice();
            } else {
                document.getElementById('home_surcharge').classList.add('hidden');
            }
            
            updateSummary();
        }
        
        function updateTotalPrice() {
            const total = basePrice + homeSurcharge;
            const totalElement = document.getElementById('total_price');
            totalElement.innerHTML = 'Total : <span class="text-green-600">' + formatPrice(total) + ' FCFA</span>';
        }
        
        function formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(price);
        }
        
        function updateSummary() {
            const summaryContent = document.getElementById('summary_content');
            const hospital = document.getElementById('hospital_id');
            const date = document.getElementById('appointment_date');
            const time = document.getElementById('appointment_time');
            const service = document.getElementById('service_id');
            const address = document.getElementById('home_address');
            const reason = document.getElementById('reason');
            
            let html = '<div class="space-y-2">';
            
            if (selectedType) {
                html += `<p class="flex items-center"><i class="fas fa-${selectedType === 'hospital' ? 'hospital' : 'home'} text-blue-600 w-5 mr-2"></i><span class="font-medium">Type:</span> <span class="ml-2">${selectedType === 'hospital' ? 'À l\'hôpital' : 'À domicile'}</span></p>`;
            }
            
            if (hospital.value) {
                html += `<p class="flex items-center"><i class="fas fa-hospital-alt text-blue-600 w-5 mr-2"></i><span class="font-medium">Établissement:</span> <span class="ml-2">${hospital.options[hospital.selectedIndex].text}</span></p>`;
            }
            
            if (date.value && time.value) {
                const dateObj = new Date(date.value);
                const dateFormatted = dateObj.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                html += `<p class="flex items-center"><i class="fas fa-calendar-alt text-blue-600 w-5 mr-2"></i><span class="font-medium">Date:</span> <span class="ml-2">${dateFormatted} à ${time.value}</span></p>`;
            }
            
            if (service.value) {
                html += `<p class="flex items-center"><i class="fas fa-stethoscope text-blue-600 w-5 mr-2"></i><span class="font-medium">Spécialité:</span> <span class="ml-2">${service.options[service.selectedIndex].text.split(' - ')[0]}</span></p>`;
            }
            
            if (selectedType === 'home' && address.value) {
                html += `<p class="flex items-start"><i class="fas fa-map-marker-alt text-blue-600 w-5 mr-2 mt-1"></i><span class="font-medium">Adresse:</span> <span class="ml-2">${address.value}</span></p>`;
            }
            
            if (reason.value) {
                html += `<p class="flex items-start"><i class="fas fa-comment-medical text-blue-600 w-5 mr-2 mt-1"></i><span class="font-medium">Motif:</span> <span class="ml-2">${reason.value.substring(0, 80)}${reason.value.length > 80 ? '...' : ''}</span></p>`;
            }
            
            if (basePrice > 0) {
                const total = selectedType === 'home' ? basePrice + homeSurcharge : basePrice;
                html += `<div class="mt-3 pt-3 border-t border-blue-200"><p class="flex items-center text-lg"><i class="fas fa-money-bill-wave text-green-600 w-5 mr-2"></i><span class="font-bold">Montant à régler:</span> <span class="ml-2 font-bold text-green-600">${formatPrice(total)} FCFA</span></p></div>`;
            }
            
            html += '</div>';
            
            if (html === '<div class="space-y-2"></div>') {
                summaryContent.innerHTML = '<p class="text-gray-500 italic">Remplissez le formulaire pour voir le résumé...</p>';
            } else {
                summaryContent.innerHTML = html;
            }
        }
        
        function resetForm() {
            document.getElementById('appointmentForm').style.display = 'none';
            document.getElementById('appointmentForm').reset();
            document.querySelectorAll('.consultation-type').forEach(card => {
                card.classList.remove('border-blue-500', 'border-green-500', 'shadow-lg');
                card.classList.add('border-gray-200');
            });
            document.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
            document.getElementById('price_display').classList.add('hidden');
            document.getElementById('service_id').innerHTML = '<option value="">Choisir d\'abord un établissement</option>';
            document.getElementById('service_id').disabled = true;
            selectedType = '';
            basePrice = 0;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

</body>
</html>