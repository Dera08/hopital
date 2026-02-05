<x-portal-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Documents Médicaux') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data='fileManager(@json($folders))'>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg min-h-[500px] flex flex-col border border-gray-200">
                
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center space-x-2 overflow-x-auto">
                    <button @click="resetPath()" class="p-2 rounded-lg hover:bg-gray-200 text-gray-600 transition-colors">
                        <i class="fas fa-home"></i>
                    </button>
                    
                    <span class="text-gray-400" x-show="currentPath.length > 0"><i class="fas fa-chevron-right text-xs"></i></span>

                    <template x-for="(crumb, index) in currentPath" :key="index">
                        <div class="flex items-center">
                            <button @click="goToLevel(index)" 
                                    class="px-3 py-1 rounded-md font-medium text-sm transition-colors hover:bg-blue-100 hover:text-blue-700"
                                    :class="index === currentPath.length - 1 ? 'text-gray-900 font-bold bg-white shadow-sm' : 'text-gray-600'"
                                    x-text="crumb">
                            </button>
                            <span class="mx-2 text-gray-400" x-show="index < currentPath.length - 1">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        </div>
                    </template>

                    <div x-show="currentPath.length === 0" class="text-gray-400 italic text-sm ml-2">
                        Vue d'ensemble des Services
                    </div>
                </div>

                <div class="p-6 flex-1 bg-white">
                    <div x-show="viewType === 'folders'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <template x-for="(item, key) in currentLevelItems" :key="key">
                            <div @click="enterFolder(key)" 
                                 class="group cursor-pointer border border-gray-100 rounded-2xl p-6 bg-gray-50 hover:bg-white hover:shadow-lg hover:border-blue-100 transition-all duration-200 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                
                                <div class="absolute -right-4 -bottom-4 text-9xl text-gray-200 opacity-20 transform group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                                    <i :class="getIcon(key)"></i>
                                </div>

                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 transition-colors duration-300"
                                     :class="getColorClass(key, item)"
                                     :style="item.color && item.color.startsWith('#') ? 'background-color: ' + item.color + '22; color: ' + item.color : ''">
                                    <i :class="getIcon(key, item)" class="text-3xl"></i>
                                </div>

                                <h3 class="font-bold text-gray-800 text-lg group-hover:text-blue-600 transition-colors" x-text="key"></h3>
                                <p class="text-xs text-gray-500 mt-1 font-medium" x-text="getSubLabel(item)"></p>
                            </div>
                        </template>

                        <div x-show="Object.keys(currentLevelItems).length === 0" class="col-span-full text-center py-12 text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                            <p>Ce dossier est vide.</p>
                        </div>
                    </div>

                <div x-show="viewType === 'files'" class="space-y-6">
                    <template x-for="(group, date) in groupedFiles" :key="date">
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 ml-1" x-text="formatDate(date)"></h3>
                            
                            <div class="space-y-3">
                                <template x-for="doc in group" :key="doc.id">
                                    <div class="flex flex-col md:flex-row items-start md:items-center p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors hover:border-blue-200 group bg-white shadow-sm">
                                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                            <i :class="doc.icon || 'fas fa-file-alt'"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 group-hover:text-blue-700 transition-colors" x-text="doc.title"></h4>
                                            <div class="flex items-center text-xs text-gray-500 mt-1 space-x-3">
                                                <span><i class="far fa-clock mr-1"></i> <span x-text="new Date(doc.date).toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'})"></span></span>
                                                <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-600" x-text="doc.type"></span>
                                            </div>
                                        </div>
                                        <div class="mt-3 md:mt-0 flex space-x-2">
                                            <button @click="shareFile(doc)" class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200 transition flex items-center" title="Partager">
                                                <i class="fas fa-share-alt mr-1.5"></i> Partager
                                            </button>
                                            
                                            <template x-if="doc.result_text">
                                                <button @click="openResult(doc)" class="px-3 py-1.5 bg-purple-600 text-white text-xs font-bold rounded-lg hover:bg-purple-700 transition shadow-sm flex items-center">
                                                    <i class="fas fa-eye mr-1.5"></i> Résultat
                                                </button>
                                            </template>

                                            <template x-if="doc.download_route">
                                                <a :href="doc.download_route" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center">
                                                    <i class="fas fa-download mr-1.5"></i> Télécharger
                                                </a>
                                            </template>
                                            <template x-if="!doc.download_route && !doc.result_text">
                                                <span class="px-3 py-1.5 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed flex items-center">
                                                    <i class="fas fa-clock mr-1.5"></i> En attente
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div x-show="filesList.length === 0" class="text-center py-12 text-gray-400">
                        <p>Aucun document dans ce dossier.</p>
                    </div>
                </div>
            </div>

            <!-- Modal Résultat -->
            <div x-show="showResultModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showResultModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showResultModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showResultModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-flask text-purple-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="currentResultTitle"></h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-2">Résultat :</p>
                                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-800 whitespace-pre-line" x-text="currentResultText"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm" @click="showResultModal = false">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

                <div class="bg-gray-50 border-t border-gray-200 px-6 py-3 text-xs text-gray-500 flex justify-between">
                    <span>
                        <span class="font-bold" x-text="viewType === 'folders' ? Object.keys(currentLevelItems).length : filesList.length"></span> éléments
                    </span>
                    <span x-text="currentPath.join(' / ') || '/'"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileManager', (initialData) => ({
                data: initialData,
                currentPath: [],
                viewType: 'folders',
                showResultModal: false,
                currentResultText: '',
                currentResultTitle: '',
                
                init() {
                    console.log('FileManager initializing...', this.data);
                    if (typeof this.data === 'string') {
                        try {
                            this.data = JSON.parse(this.data);
                        } catch (e) {
                            console.error('Failed to parse initialData', e);
                            this.data = {};
                        }
                    }
                    if (!this.data) this.data = {};
                },

                openResult(doc) {
                    this.currentResultTitle = doc.title.replace(' (En cours)', '');
                    this.currentResultText = doc.result_text;
                    this.showResultModal = true;
                },

                get currentLevelItems() {
                    let current = this.data;
                    for (let folder of this.currentPath) {
                        if (!current || !current[folder]) return {};
                        current = current[folder].children ? current[folder].children : current[folder];
                    }
                    
                    if (Array.isArray(current)) {
                        this.viewType = 'files';
                        return {};
                    }
                    
                    this.viewType = 'folders';
                    return current || {};
                },

                get filesList() {
                    let current = this.data;
                    for (let folder of this.currentPath) {
                        if (!current[folder]) return [];
                        current = current[folder].children ? current[folder].children : current[folder];
                    }
                    return Array.isArray(current) ? current : [];
                },

                get groupedFiles() {
                    const current = this.filesList;
                    const grouped = {};
                    current.forEach(doc => {
                       // Extraire YYYY-MM-DD
                       const dateObj = new Date(doc.date);
                       // Format simple pour tri
                       const d = dateObj.toISOString().split('T')[0]; 
                       if (!grouped[d]) grouped[d] = [];
                       grouped[d].push(doc);
                    });
                    // Trier les clés (dates) décroissant
                    return Object.keys(grouped).sort().reverse().reduce((obj, key) => {
                        obj[key] = grouped[key];
                        return obj;
                    }, {});
                },

                enterFolder(key) {
                    this.currentPath.push(key);
                },

                goToLevel(index) {
                    this.currentPath = this.currentPath.slice(0, index + 1);
                },

                resetPath() {
                    this.currentPath = [];
                },

                formatDate(dateString) {
                    if(!dateString) return '';
                    return new Date(dateString).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
                },

                getIcon(key, item = null) {
                    if (item && item.icon) return item.icon;
                    const k = key.toLowerCase();
                    if (k.includes('maison')) return 'fas fa-home';
                    if (k.includes('hôpital')) return 'fas fa-hospital-alt';
                    if (k.includes('admission')) return 'fas fa-bed';
                    if (k.includes('lab')) return 'fas fa-flask';
                    if (k.includes('test')) return 'fas fa-vial'; // Icone pour Test
                    return 'fas fa-folder';
                },

                getSubLabel(item) {
                    if (!item) return '0 élément';
                    if (Array.isArray(item)) return item.length + ' documents';
                    let target = item.children ? item.children : item;
                    return Object.keys(target).length + ' éléments';
                },

                getColorClass(key, item) {
                    if (!item || (item.color && item.color.startsWith('#'))) return '';
                    if (item.color === 'blue') return 'bg-blue-100 text-blue-600';
                    if (item.color === 'green') return 'bg-green-100 text-green-600';
                    if (item.color === 'red') return 'bg-red-100 text-red-600';
                    return 'bg-indigo-100 text-indigo-600';
                },

                async shareFile(doc) {
                    if (navigator.share) {
                        try {
                            await navigator.share({
                                title: doc.title,
                                text: 'Document partagé depuis mon Espace Patient',
                                url: doc.download_route || window.location.href // Fallback si pas de fichier
                            });
                        } catch (err) {
                            console.log('Share canceled', err);
                        }
                    } else {
                        // Fallback: Copy link
                        if (doc.download_route) {
                            navigator.clipboard.writeText(doc.download_route);
                            alert('Lien copié dans le presse-papier !');
                        } else {
                            alert('Fichier non disponible pour le partage.');
                        }
                    }
                }
            }));
        });
    </script>
</x-portal-layout>