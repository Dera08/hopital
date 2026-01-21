@extends('layouts.nurse')

@section('title', 'Dashboard Infirmière')
@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen">

<div x-data="nurseDashboard()" class="pb-10">
    <header class="bg-gradient-to-r from-pink-600 to-purple-600 text-white p-4 shadow-lg">
        <div class="max-w-[1600px] mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-xl">
                    <i data-lucide="shield-plus" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">HospitISIS</h1>
                    <p class="text-pink-100 text-xs">{{ auth()->user()?->role === 'nurse' ? 'Infirmière' : 'Infirmier' }} - {{ auth()->user()?->name ?? 'User' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
                <button onclick="document.getElementById('logout-form').submit();" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition text-sm font-medium">
                    Déconnexion
                </button>
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center font-bold">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 2)) }}
                </div>
            </div>
        </div>
    </header>

    <nav class="bg-white shadow-md border-b sticky top-0 z-40">
        <div class="max-w-[1600px] mx-auto px-4">
            <div class="flex gap-1 overflow-x-auto">
                <template x-for="tab in tabs" :key="tab.id">
                    <button 
                        @click="selectedTab = tab.id"
                        class="px-4 py-3 font-medium transition flex items-center gap-2 whitespace-nowrap border-b-2"
                        :class="selectedTab === tab.id ? 'text-pink-600 border-pink-600' : 'text-gray-600 border-transparent hover:text-pink-600'"
                    >
                        <i :class="'w-4 h-4'" :data-lucide="tab.icon"></i>
                        <span x-text="tab.label"></span>
                        <span x-show="tab.badge > 0" class="bg-pink-500 text-white text-xs px-2 py-0.5 rounded-full font-bold" x-text="tab.badge"></span>
                    </button>
                </template>
            </div>
        </div>
    </nav>

    <main class="max-w-[1600px] mx-auto p-4">
        
        <div x-show="selectedTab === 'dashboard'" class="space-y-4" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-pink-500 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-xs font-medium uppercase">RDV Aujourd'hui</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1" x-text="getStats().rdvAujourdhui"></p>
                    </div>
                    <div class="text-pink-600 opacity-20"><i data-lucide="calendar" class="w-8 h-8"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-xs font-medium uppercase">Dossiers Envoyés</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1" x-text="getStats().dossiersEnvoyes"></p>
                    </div>
                    <div class="text-purple-600 opacity-20"><i data-lucide="send" class="w-8 h-8"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-xs font-medium uppercase">Mes Patients</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1" x-text="getStats().patients"></p>
                    </div>
                    <div class="text-blue-600 opacity-20"><i data-lucide="users" class="w-8 h-8"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-orange-500 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-xs font-medium uppercase">À Préparer</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1" x-text="getStats().aPreparer"></p>
                    </div>
                    <div class="text-orange-600 opacity-20"><i data-lucide="clock" class="w-8 h-8"></i></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Rendez-vous à Préparer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="apt in doctorAppointments.filter(a => a.status === 'pending')" :key="apt.id">
                        <div class="border-2 border-pink-200 rounded-lg p-4 bg-pink-50">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg" x-text="apt.patientName"></h3>
                                    <p class="text-sm text-gray-600" x-text="apt.patientId + ' • ' + apt.age + ' ans'"></p>
                                </div>
                                <span class="bg-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold" x-text="apt.time"></span>
                            </div>
                            <div class="bg-white rounded p-3 mb-3 text-sm text-gray-700">
                                <p><span class="font-medium">Motif:</span> <span x-text="apt.reason"></span></p>
                                <p><span class="font-medium">Médecin:</span> <span x-text="apt.doctor"></span></p>
                            </div>
                            <button @click="handlePreparePatient(apt.id)" class="w-full bg-pink-600 text-white py-2 rounded-lg font-medium hover:bg-pink-700 transition flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i> Préparer et Envoyer
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="selectedTab === 'appointments'" x-cloak x-transition>
            <div class="bg-white rounded-xl shadow-md p-4">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Rendez-vous du Médecin</h2>
                <div class="space-y-3">
                    <template x-for="apt in doctorAppointments" :key="apt.id">
                        <div class="border-2 rounded-lg p-4" :class="apt.status === 'sent' ? 'bg-green-50 border-green-300' : 'bg-white border-gray-200'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-pink-100 p-3 rounded-lg text-pink-600"><i data-lucide="calendar"></i></div>
                                    <div>
                                        <h3 class="font-bold text-slate-800" x-text="apt.patientName"></h3>
                                        <p class="text-sm text-pink-600 font-medium" x-text="apt.date + ' à ' + apt.time"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <template x-if="apt.status === 'sent'">
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">ENVOYÉ</span>
                                    </template>
                                    <template x-if="apt.status === 'pending'">
                                        <button @click="handlePreparePatient(apt.id)" class="bg-pink-600 text-white px-4 py-2 rounded-lg text-sm">Préparer</button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="selectedTab === 'patients'" x-cloak x-transition>
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Mes Patients</h2>
                    <div class="flex gap-2">
                        <input type="text" x-model="searchTerm" placeholder="Rechercher..." class="px-3 py-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-pink-500">
                        <button @click="showAddPatientModal = true" class="bg-pink-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i> Nouveau Patient
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="patient in filteredPatients()" :key="patient.id">
                        <div class="border-2 rounded-lg p-4" :class="patient.hasAppointment ? 'bg-pink-50 border-pink-300' : 'bg-white border-gray-200'">
                            <div class="flex justify-between">
                                <div class="flex gap-3">
                                    <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center text-white font-bold" x-text="patient.name.split(' ').map(n => n[0]).join('')"></div>
                                    <div>
                                        <p class="font-bold" x-text="patient.name"></p>
                                        <p class="text-sm text-gray-500" x-text="patient.age + ' ans • ' + patient.ipu"></p>
                                    </div>
                                </div>
                                <button @click="openPrepareFromPatient(patient)" class="bg-pink-100 text-pink-600 px-3 py-1 rounded-lg text-xs h-fit">Envoyer</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="selectedTab === 'sent'" x-cloak x-transition>
            <div class="bg-white rounded-xl shadow-md p-4">
                <h2 class="text-xl font-bold mb-4">Dossiers Envoyés</h2>
                <div class="space-y-3">
                    <template x-if="sentFiles.length === 0">
                        <div class="text-center py-10 text-gray-400">Aucun dossier envoyé aujourd'hui</div>
                    </template>
    <template x-for="file in sentFiles" :key="file.id">
    <div class="border-2 border-yellow-300 bg-yellow-50 rounded-lg p-4 flex justify-between items-center">
        <div>
            <p class="font-bold" x-text="file.patientName"></p>
            <p class="text-sm text-gray-600" x-text="'Motif: ' + file.reason"></p>
            <p class="text-xs text-gray-400 mt-1" x-text="'Envoyé le: ' + file.sentAt"></p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">EN ATTENTE</span>
            
            <button @click="deleteVital(file.id)" class="text-red-500 hover:text-red-700 transition p-1 rounded-full hover:bg-red-50">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
        </div>
    </div>
</template>
                </div>
            </div>
        </div>
    </main>

    <div x-show="showSuccessToast"
         x-transition
         class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-2xl z-[100] flex items-center gap-2">
        <i data-lucide="check-circle"></i>
        <span>Dossier transmis avec succès !</span>
    </div>

    <div x-show="showSendModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" x-cloak x-transition>
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">Envoyer au Médecin</h2>
            <div x-show="selectedPatient" class="bg-pink-50 rounded-lg p-4 mb-4">
                <p class="font-bold" x-text="selectedPatient?.patientName || selectedPatient?.name"></p>
                <p class="text-sm text-gray-600" x-text="(selectedPatient?.patientId || selectedPatient?.ipu) + ' • ' + selectedPatient?.age + ' ans'"></p>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Urgence</label>
                    <select x-model="sendFormData.urgency" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="normale">Urgence Normale</option>
                        <option value="urgent">Urgent</option>
                        <option value="critique">Critique</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Motif de consultation *</label>
                    <textarea x-model="sendFormData.reason" placeholder="Ex: Douleurs abdominales..." class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-pink-500" rows="3"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Température (°C) *</label>
                        <input type="number" step="0.1" x-model="sendFormData.vitals.temp" placeholder="37.5" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Pouls (BPM) *</label>
                        <input type="number" x-model="sendFormData.vitals.pulse" placeholder="80" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Poids (Kg)</label>
                        <input type="number" step="0.1" x-model="sendFormData.vitals.weight" placeholder="70" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Taille (cm)</label>
                        <input type="number" x-model="sendFormData.vitals.height" placeholder="175" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button @click="showSendModal = false" class="flex-1 bg-gray-200 py-3 rounded-lg font-medium">Annuler</button>
                <button @click="handleSendToDoctor" class="flex-1 bg-pink-600 text-white py-3 rounded-lg font-medium">Envoyer</button>
            </div>
        </div>
    </div>

    <div x-show="showAddPatientModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" x-cloak x-transition>
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 text-center">
            <h2 class="text-2xl font-bold mb-4">Nouveau Patient</h2>
            <div class="space-y-4 text-left">
                <input type="text" x-model="newPatientData.name" placeholder="Nom complet *" class="w-full px-3 py-2 border rounded-lg">
                <input type="number" x-model="newPatientData.age" placeholder="Âge *" class="w-full px-3 py-2 border rounded-lg">
                <select x-model="newPatientData.bloodType" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Groupe Sanguin *</option>
                    <option value="A+">A+</option><option value="O+">O+</option><option value="AB+">AB+</option>
                </select>
            </div>
            <div class="flex gap-3 mt-6">
                <button @click="showAddPatientModal = false" class="flex-1 bg-gray-200 py-3 rounded-lg">Fermer</button>
                <button @click="handleAddPatient" class="flex-1 bg-pink-600 text-white py-3 rounded-lg font-medium">Ajouter</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
function nurseDashboard() {
    return {
        selectedTab: 'dashboard',
        showSendModal: false,
        showAddPatientModal: false,
        showSuccessToast: false,
        selectedPatient: null,
        searchTerm: '',
        tabs: [
            { id: 'dashboard', icon: 'layout-dashboard', label: 'Tableau de Bord', badge: 0 },
            { id: 'appointments', icon: 'calendar-days', label: 'RDV Médecin', badge: 3 },
            { id: 'patients', icon: 'users', label: 'Mes Patients', badge: 0 },
            { id: 'sent', icon: 'send', label: 'Envoyés', badge: 1 }
        ],
        
        doctorAppointments: [
            @foreach($appointments as $apt)
            {
                id: {{ $apt->id }},
                patientName: '{{ $apt->patient->name }}',
                patientId: '{{ $apt->patient->ipu }}',
                age: {{ \Carbon\Carbon::parse($apt->patient->dob)->age }},
                date: '{{ $apt->appointment_datetime->format('d/m/Y') }}',
                time: '{{ $apt->appointment_datetime->format('H:i') }}',
                reason: '{{ $apt->reason }}',
                doctor: '{{ $apt->doctor ? $apt->doctor->name : "N/A" }}',
                status: 'pending'
            },
            @endforeach
        ],

        sentFiles: [
            @foreach($sentFiles as $file)
            {
                id: {{ $file->id }},
                patientName: '{{ $file->patient_name }}',
                reason: '{{ $file->reason }}',
                sentAt: '{{ $file->created_at->format('H:i') }}',
                status: 'pending'
            },
            @endforeach
        ],

        myPatients: [
            @foreach($myPatients as $admission)
            { id: {{ $admission->id }}, name: '{{ $admission->patient->name }}', ipu: '{{ $admission->patient->ipu ?? "PAT-".rand(100,999) }}', age: {{ \Carbon\Carbon::parse($admission->patient->birth_date)->age ?? 0 }}, bloodType: 'A+', hasAppointment: false },
            @endforeach
        ],

        sendFormData: { 
            urgency: 'normale', 
            reason: '', 
            vitals: { temp: '', pulse: '', weight: '', height: '' } 
        },

        newPatientData: { name: '', age: '', bloodType: '' },

        init() {
            lucide.createIcons();
            this.$watch('selectedTab', () => { this.$nextTick(() => lucide.createIcons()); });
        },

        // FONCTION DE RÉINITIALISATION (Correctif pour le mélange Ana/Ama)
        resetForm() {
            this.sendFormData = { 
                urgency: 'normale', 
                reason: '', 
                vitals: { temp: '', pulse: '', weight: '', height: '' } 
            };
        },

        getStats() {
            const pendings = this.doctorAppointments.filter(a => a.status === 'pending').length;
            this.tabs[1].badge = pendings;
            this.tabs[3].badge = this.sentFiles.length;
            this.tabs[2].badge = this.myPatients.length;
            return { rdvAujourdhui: pendings, dossiersEnvoyes: this.sentFiles.length, patients: this.myPatients.length, aPreparer: pendings };
        },

        filteredPatients() {
            return this.myPatients.filter(p => p.name.toLowerCase().includes(this.searchTerm.toLowerCase()) || p.ipu.toLowerCase().includes(this.searchTerm.toLowerCase()));
        },

        handlePreparePatient(id) {
            this.resetForm(); // On vide avant d'ouvrir
            this.selectedPatient = this.doctorAppointments.find(a => a.id === id);
            this.showSendModal = true;
        },

        openPrepareFromPatient(patient) {
            this.resetForm(); // On vide avant d'ouvrir
            this.selectedPatient = patient;
            this.showSendModal = true;
        },

        async handleSendToDoctor() {
            // Validation avec alerte pour aider l'utilisateur
            if (!this.sendFormData.reason) return alert("Le motif de consultation est obligatoire.");
            if (!this.sendFormData.vitals.temp) return alert("La température est obligatoire.");
            if (!this.sendFormData.vitals.pulse) return alert("Le pouls est obligatoire.");

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/nurse/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        patient_name: this.selectedPatient.patientName || this.selectedPatient.name,
                        patient_ipu: this.selectedPatient.patientId || this.selectedPatient.ipu,
                        urgency: this.sendFormData.urgency,
                        reason: this.sendFormData.reason,
                        temperature: this.sendFormData.vitals.temp, 
                        pulse: this.sendFormData.vitals.pulse,
                        weight: this.sendFormData.vitals.weight,
                        height: this.sendFormData.vitals.height,
                        blood_pressure: "12/8"
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    this.resetForm(); // On vide après succès
                    this.showSendModal = false;
                    this.showSuccessToast = true;
                    setTimeout(() => {
                        this.showSuccessToast = false;
                        window.location.reload(); 
                    }, 1500);
                } else {
                    alert("Erreur serveur : " + (result.message || "Vérifiez les données."));
                }
            } catch (error) {
                console.error("Erreur:", error);
                alert("Impossible de joindre le serveur. Vérifiez votre connexion.");
            }
        },

        handleAddPatient() {
            if (!this.newPatientData.name || !this.newPatientData.age) return alert('Veuillez remplir le nom et l\'âge');
            this.myPatients.push({ id: Date.now(), name: this.newPatientData.name, age: this.newPatientData.age, ipu: 'PAT' + Math.floor(Math.random()*9000), bloodType: this.newPatientData.bloodType, hasAppointment: false });
            this.showAddPatientModal = false;
            this.newPatientData = { name: '', age: '', bloodType: '' };
        },

        async deleteVital(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`${window.location.origin}/nurse/vital/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    this.sentFiles = this.sentFiles.filter(file => file.id !== id);
                    this.tabs[3].badge = this.sentFiles.length;
                    alert('Dossier supprimé avec succès !');
                }
            } catch (error) {
                console.error('Erreur réseau:', error);
            }
        }
    }
}
</script>
@endpush