<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PatientVital;
use App\Models\Admission;
use App\Models\Patient;

class DebugHospitalizationLink extends Command
{
    protected $signature = 'debug:hospitalization';
    protected $description = 'Debug the link between PatientVital and Admission';

    public function handle()
    {
        $this->info('Starting Debug...');

        // 1. Get the latest PatientVital records
        $vitals = PatientVital::latest()->take(5)->get();

        foreach ($vitals as $vital) {
            $this->newLine();
            $this->info("--------------------------------------------------");
            $this->info("Vital ID: {$vital->id}");
            $this->info("Created At: {$vital->created_at}");
            $this->info("Patient IPU: {$vital->patient_ipu}");
            $this->info("Explicit Doctor ID: " . ($vital->doctor_id ?? 'NULL'));

            // Find patient to get ID
            $patient = Patient::where('ipu', $vital->patient_ipu)->first();
            if (!$patient) {
                $this->error("Patient not found for IPU: {$vital->patient_ipu}");
                continue;
            }
            $this->info("Patient ID: {$patient->id}");

            // Check Accessor
            $related = $vital->related_admission;
            if ($related) {
                $this->info("MATCH FOUND via Accessor!");
                $this->info("Admission ID: {$related->id}");
                $this->info("Doctor ID: {$related->doctor_id}");
                $this->info("Dates: {$related->admission_date} - {$related->discharge_date}");
            } else {
                $this->warn("No match via Accessor.");
            }

            // Dump ALL admissions for this patient
            $admissions = Admission::withoutGlobalScopes()->where('patient_id', $patient->id)->get();
            $this->info("Total Admissions for Patient: " . $admissions->count());
            foreach ($admissions as $adm) {
                $this->line(" - Adm ID: {$adm->id} | Dates: {$adm->admission_date} -> " . ($adm->discharge_date ?? 'NULL'));
                
                // Manual check logic
                $matches = ($adm->admission_date <= $vital->created_at) && 
                           ($adm->discharge_date === null || $adm->discharge_date >= $vital->created_at);
                $this->line("   -> Logic Check: " . ($matches ? 'SHOULD MATCH' : 'NO MATCH'));
            }
        }
    }
}
