<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Prestation;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefinedPrestationsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Définir les prestations réalistes par type de service
        // Les clés doivent correspondre (partiellement ou totalement) aux noms des services créés
        // Les clés sont utilisées pour le matching (stripos). On utilise des termes génériques pour matcher large.
        $prestationsMap = [
            'Urgence' => [ // Matche "Urgences", "Médecine d'Urgence", "Urgence"
                ['name' => 'Consultation Urgence', 'price' => 15000, 'category' => 'consultation'],
                ['name' => 'Suture simple', 'price' => 25000, 'category' => 'soins'],
                ['name' => 'Pansement (petite plaie)', 'price' => 5000, 'category' => 'soins'],
                ['name' => 'Injection IM/IV', 'price' => 3000, 'category' => 'soins'],
                ['name' => 'Pose de perfusion', 'price' => 10000, 'category' => 'soins'],
                ['name' => 'Oxygénothérapie (heure)', 'price' => 5000, 'category' => 'soins'],
            ],
            'Cardio' => [ // Matche "Cardiologie", "Cardiologue"
                ['name' => 'Consultation Cardiologie', 'price' => 25000, 'category' => 'consultation'],
                ['name' => 'Électrocardiogramme (ECG)', 'price' => 15000, 'category' => 'examen'],
                ['name' => 'Échographie Doppler Cœur', 'price' => 45000, 'category' => 'examen'],
                ['name' => 'Holter Tensionnel (MAPA)', 'price' => 30000, 'category' => 'examen'],
                ['name' => 'Épreuve d\'effort', 'price' => 50000, 'category' => 'examen'],
            ],
            'Pédiatrie' => [ // Matche "Pédiatrie"
                ['name' => 'Consultation Pédiatrie', 'price' => 20000, 'category' => 'consultation'],
                ['name' => 'Vaccination (acte)', 'price' => 5000, 'category' => 'soins'],
                ['name' => 'Nébulisation', 'price' => 8000, 'category' => 'soins'],
                ['name' => 'Bilan de santé enfant', 'price' => 25000, 'category' => 'examen'],
            ],
            'Chirurg' => [ // Matche "Chirurgie", "Chirurgien"
                ['name' => 'Consultation Chirurgie', 'price' => 30000, 'category' => 'consultation'],
                ['name' => 'Pansement post-opératoire', 'price' => 10000, 'category' => 'soins'],
                ['name' => 'Ablation de fils/agrafes', 'price' => 10000, 'category' => 'soins'],
                ['name' => 'Petite chirurgie (kyste/lipome)', 'price' => 75000, 'category' => 'soins'],
                ['name' => 'Circoncision', 'price' => 100000, 'category' => 'soins'],
            ],
            'Matern' => [ // Matche "Maternité", "MATERNITE"
                ['name' => 'Consultation Gynécologie', 'price' => 20000, 'category' => 'consultation'],
                ['name' => 'Consultation Prénatale (CPN)', 'price' => 15000, 'category' => 'consultation'],
                ['name' => 'Échographie Obstétricale', 'price' => 25000, 'category' => 'examen'],
                ['name' => 'Monitoring Fœtal (RCF)', 'price' => 15000, 'category' => 'examen'],
                ['name' => 'Accouchement voie basse simple', 'price' => 150000, 'category' => 'soins'],
                ['name' => 'Césarienne', 'price' => 350000, 'category' => 'soins'],
            ],
            'Radio' => [ // Matche "Radiologie", "Radio"
                ['name' => 'Échographie Abdominale', 'price' => 25000, 'category' => 'examen'],
                ['name' => 'Échographie Pelvienne', 'price' => 25000, 'category' => 'examen'],
                ['name' => 'Radio Thorax', 'price' => 15000, 'category' => 'examen'],
                ['name' => 'Scanner Cérébral', 'price' => 80000, 'category' => 'examen'],
                ['name' => 'Radio Osseuse (membre)', 'price' => 15000, 'category' => 'examen'],
            ],
            'Ophtalm' => [ // Matche "Ophtalmologie", "Ophtalmo"
                ['name' => 'Consultation Ophtalmologie', 'price' => 20000, 'category' => 'consultation'],
                ['name' => 'Fond d\'œil', 'price' => 15000, 'category' => 'examen'],
                ['name' => 'Mesure tension oculaire', 'price' => 10000, 'category' => 'examen'],
                ['name' => 'Réfraction', 'price' => 10000, 'category' => 'examen'],
            ],
            'Dermat' => [ // Matche "Dermatologie", "Dermato"
                ['name' => 'Consultation Dermatologie', 'price' => 20000, 'category' => 'consultation'],
                ['name' => 'Cryothérapie (verrue)', 'price' => 15000, 'category' => 'soins'],
                ['name' => 'Biopsie cutanée', 'price' => 30000, 'category' => 'soins'],
            ],
            'ORL' => [ // Matche "ORL"
                ['name' => 'Consultation ORL', 'price' => 20000, 'category' => 'consultation'],
                ['name' => 'Audiogramme', 'price' => 25000, 'category' => 'examen'],
                ['name' => 'Lavage d\'oreille', 'price' => 10000, 'category' => 'soins'],
            ],
            'Onco' => [ // Matche "Oncologie"
                ['name' => 'Consultation Oncologie', 'price' => 30000, 'category' => 'consultation'],
                ['name' => 'Séance de Chimiothérapie', 'price' => 50000, 'category' => 'soins'],
            ],
            'Laborat' => [ // Matche "Laboratoire"
                // Tests Fréquents
                ['name' => 'NFS (Hémogramme)', 'price' => 5000, 'category' => 'examen'],
                ['name' => 'CRP (Protéine C-Réactive)', 'price' => 4000, 'category' => 'examen'],
                ['name' => 'Glycémie', 'price' => 2000, 'category' => 'examen'],
                ['name' => 'Créatininémie', 'price' => 3500, 'category' => 'examen'],
                ['name' => 'Transaminases (ASAT/ALAT)', 'price' => 4000, 'category' => 'examen'],
                ['name' => 'Goutte Épaisse / TDR Palu', 'price' => 2000, 'category' => 'examen'],
                
                // Hématologie
                ['name' => 'Vitesse de Sédimentation (VS)', 'price' => 2000, 'category' => 'examen'],
                ['name' => 'TP / TCA', 'price' => 5000, 'category' => 'examen'],
                ['name' => 'Groupe Sanguin', 'price' => 3000, 'category' => 'examen'],

                // Biochimie
                ['name' => 'Urée', 'price' => 2500, 'category' => 'examen'],
                ['name' => 'Bilan Lipidique', 'price' => 8000, 'category' => 'examen'],

                // Microbiologie
                ['name' => 'ECBU', 'price' => 5000, 'category' => 'examen'],
                ['name' => 'Hémoculture', 'price' => 8000, 'category' => 'examen'],
                ['name' => 'Coproculture', 'price' => 5000, 'category' => 'examen'],
                ['name' => 'Widal (Typhoïde)', 'price' => 4500, 'category' => 'examen'],
            ],
            'Générale' => [ // Matche "Médecine générale", "Medecin générale"
                ['name' => 'Consultation Générale', 'price' => 15000, 'category' => 'consultation'],
                ['name' => 'Certificat médical', 'price' => 5000, 'category' => 'consultation'],
            ],
        ];

        // 2. Nettoyer les anciennes prestations pour éviter les doublons/erreurs (Optionnel mais recommandé si on refait tout)
        // DB::table('prestations')->truncate(); // Attention : à ne faire que si l'on veut tout reset
        // Ici, on va plutôt supprimer les prestations existantes pour les services qu'on va traiter.
        
        $hospitals = Hospital::all();

        foreach ($hospitals as $hospital) {
            $services = Service::where('hospital_id', $hospital->id)->get();

            foreach ($services as $service) {
                // Trouver le type de service correspondant dans notre map
                $matchedType = null;
                foreach ($prestationsMap as $type => $data) {
                    if (stripos($service->name, $type) !== false) {
                        $matchedType = $type;
                        break;
                    }
                }

                // Si on trouve une correspondance (ex: "Service de Cardiologie" matche "Cardiologie")
                if ($matchedType) {
                    // On supprime les anciennes prestations de ce service pour éviter les incohérences (ex: "Pansement" en Cardio)
                    // Mais on garde celles qui sont peut-être utilisées dans des factures ? 
                    // Pour un fix propre demandé par l'user ("refait moi cela"), on peut supprimer ou désactiver.
                    // On va supprimer celles qui ne sont pas dans la nouvelle liste.
                    
                    // Option brutale pour le seed : delete et recreate. 
                    // Si contraintes de clé étrangère, cela échouera. Dans ce cas, on update ou on ignore.
                    // Pour ce seed de correction, supposons qu'on peut nettoyer.
                    try {
                        Prestation::where('service_id', $service->id)->delete();
                    } catch (\Exception $e) {
                        // Si on ne peut pas supprimer (déjà utilisé), on les marque inactives ?
                        // Ou on continue simplement.
                        $this->command->warn("Impossible de supprimer les anciennes prestations pour {$service->name} (probablement utilisées). Ajout des nouvelles uniquement.");
                    }

                    // Créer les nouvelles prestations
                    foreach ($prestationsMap[$matchedType] as $prestationData) {
                        Prestation::create([
                            'hospital_id' => $hospital->id,
                            'service_id' => $service->id,
                            'name' => $prestationData['name'],
                            'code' => strtoupper(substr($matchedType, 0, 3)) . '-' . strtoupper(substr(\Illuminate\Support\Str::slug($prestationData['name']), 0, 4)) . '-' . $hospital->id . '-' . rand(100, 999), 
                            'category' => $prestationData['category'],
                            'price' => $prestationData['price'],
                            'description' => $prestationData['name'],
                            'is_active' => true,
                        ]);
                    }
                    
                    $this->command->info("Prestations refaites pour : {$service->name} ({$hospital->name})");
                } else {
                    // Service inconnu ? On met au moins une consultation standard
                     try {
                        Prestation::firstOrCreate([
                            'hospital_id' => $hospital->id,
                            'service_id' => $service->id,
                            'name' => 'Consultation ' . $service->name,
                        ], [
                            'code' => 'CONS-' . $service->id . '-' . rand(100, 999),
                            'category' => 'consultation',
                            'price' => 15000,
                            'description' => 'Consultation standard',
                            'is_active' => true,
                        ]);
                    } catch (\Exception $e) {}
                }
            }
        }
    }
}
