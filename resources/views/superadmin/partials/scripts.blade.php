<script>
    /**
     * Affiche une notification toast premium
     */
    function showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-8 right-8 z-[1000] flex items-center gap-4 px-8 py-5 rounded-[2rem] shadow-2xl transition-all duration-500 transform translate-y-20 opacity-0 border-2 ${
            type === 'success' 
            ? 'bg-white border-emerald-100 text-emerald-900 shadow-emerald-100' 
            : 'bg-white border-red-100 text-red-900 shadow-red-100'
        }`;

        const icon = type === 'success' 
            ? '<div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-200"><i class="bi bi-check-lg text-xl"></i></div>'
            : '<div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-red-200"><i class="bi bi-exclamation-triangle-fill text-xl"></i></div>';

        toast.innerHTML = `
            ${icon}
            <div class="flex flex-col">
                <span class="text-sm font-black uppercase tracking-widest opacity-40">${type === 'success' ? 'Succès' : 'Attention'}</span>
                <span class="font-black text-lg tracking-tight">${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Animation entrée
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 100);

        // Sortie automatique
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }

    /**
     * Alterne l'état actif/inactif d'un hôpital
     */
    function toggleHospitalStatus(hospitalId, isActive) {
        fetch(`{{ url('admin-system/hospitals') }}/${hospitalId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_active: isActive })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(isActive ? 'Hôpital activé avec succès' : 'Hôpital désactivé');
                // Optionnel: rafraîchir certains éléments UI si nécessaire
            } else {
                showNotification('Erreur lors du changement de statut', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur réseau ou serveur', 'error');
        });
    }
    /**
     * Formate un nombre en monnaie FCFA
     */
    function numberFormat(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    }
</script>
