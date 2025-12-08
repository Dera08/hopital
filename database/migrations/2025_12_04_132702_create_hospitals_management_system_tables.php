<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des services hospitaliers
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Modifier la table users existante au lieu de la créer
        Schema::table('users', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'doctor', 'nurse', 'administrative'])->default('administrative')->after('password');
            }
            if (!Schema::hasColumn('users', 'service_id')) {
                $table->foreignId('service_id')->nullable()->after('role')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('service_id');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('users', 'registration_number')) {
                $table->string('registration_number')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'mfa_enabled')) {
                $table->boolean('mfa_enabled')->default(false)->after('registration_number');
            }
            if (!Schema::hasColumn('users', 'mfa_secret')) {
                $table->string('mfa_secret')->nullable()->after('mfa_enabled');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Table des patients
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('ipu')->unique()->comment('Identifiant Patient Unique');
            $table->string('name');
            $table->string('first_name');
            $table->date('dob')->comment('Date of Birth');
            $table->enum('gender', ['M', 'F', 'Other']);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('blood_group')->nullable();
            $table->json('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Table des chambres/lits
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number');
            $table->integer('bed_capacity')->default(1);
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['available', 'occupied', 'cleaning', 'maintenance'])->default('available');
            $table->string('type')->nullable()->comment('standard, VIP, isolation');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table des admissions
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->enum('admission_type', ['emergency', 'scheduled', 'transfer'])->default('scheduled');
            $table->enum('status', ['active', 'discharged', 'transferred'])->default('active');
            $table->text('admission_reason')->nullable();
            $table->text('discharge_summary')->nullable();
            $table->timestamps();
        });

        // Table des rendez-vous
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->dateTime('appointment_datetime');
            $table->integer('duration')->default(30)->comment('Duration in minutes');
            $table->enum('status', ['scheduled', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('scheduled');
            $table->enum('type', ['consultation', 'follow_up', 'emergency'])->default('consultation');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Table des observations cliniques (constantes)
        Schema::create('clinical_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['blood_pressure', 'temperature', 'heart_rate', 'weight', 'height', 'oxygen_saturation', 'glucose']);
            $table->string('value');
            $table->string('unit');
            $table->timestamp('observation_datetime');
            $table->text('notes')->nullable();
            $table->boolean('is_critical')->default(false);
            $table->timestamps();
        });

        // Table des dossiers médicaux (notes cliniques)
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by_id')->constrained('users')->cascadeOnDelete();
            $table->enum('record_type', ['consultation', 'diagnosis', 'history', 'note', 'report']);
            $table->text('content');
            $table->timestamp('record_datetime');
            $table->boolean('is_validated')->default(false);
            $table->foreignId('validated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });

        // Table des prescriptions
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->string('medication');
            $table->string('dosage');
            $table->string('frequency');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_hash')->nullable();
            $table->boolean('allergy_checked')->default(false);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });

        // Table des notes de soins infirmiers
        Schema::create('nursing_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('note_type', ['medication_administration', 'wound_care', 'hygiene', 'observation', 'other']);
            $table->text('content');
            $table->timestamp('care_datetime');
            $table->string('signature_hash');
            $table->timestamps();
        });

        // Table des documents médicaux
        Schema::create('medical_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by_id')->constrained('users')->cascadeOnDelete();
            $table->enum('document_type', ['lab_result', 'imaging', 'report', 'discharge_summary', 'consent']);
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->boolean('is_validated')->default(false);
            $table->foreignId('validated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_document_id')->nullable()->constrained('medical_documents')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Table de facturation
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained()->nullOnDelete();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['draft', 'pending', 'paid', 'cancelled'])->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Table des lignes de facturation
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('code')->nullable();
            $table->timestamps();
        });

        // Table des logs d'audit
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('resource_type');
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at');
            $table->boolean('is_encrypted')->default(false);
            
            $table->index(['user_id', 'created_at']);
            $table->index(['resource_type', 'resource_id']);
        });

        // Table des alertes cliniques
        Schema::create('clinical_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('triggered_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('alert_type', ['drug_interaction', 'allergy', 'critical_value', 'prescription_error']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('message');
            $table->boolean('is_acknowledged')->default(false);
            $table->foreignId('acknowledged_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });

        // Table de disponibilité des médecins
        Schema::create('doctor_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->default(30)->comment('Minutes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table des absences/congés médecins
        Schema::create('doctor_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('leave_type', ['vacation', 'sick', 'conference', 'other'])->default('vacation');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_leaves');
        Schema::dropIfExists('doctor_availability');
        Schema::dropIfExists('clinical_alerts');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('medical_documents');
        Schema::dropIfExists('nursing_notes');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('clinical_observations');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('admissions');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('patients');
        
        // Supprimer les colonnes ajoutées à users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn([
                'role', 'service_id', 'is_active', 'phone', 
                'registration_number', 'mfa_enabled', 'mfa_secret', 'deleted_at'
            ]);
        });
        
        Schema::dropIfExists('services');
    }
};
 