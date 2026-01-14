<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('document_type', ['lab_result', 'imaging', 'report', 'discharge_summary', 'consent'])->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->foreignId('validated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_document_id')->nullable()->constrained('medical_documents')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            $table->dropForeign(['parent_document_id']);
            $table->dropColumn('parent_document_id');
            $table->dropColumn('version');
            $table->dropColumn('validated_at');
            $table->dropForeign(['validated_by_id']);
            $table->dropColumn('validated_by_id');
            $table->dropColumn('is_validated');
            $table->dropColumn('file_size');
            $table->dropColumn('mime_type');
            $table->dropColumn('file_name');
            $table->dropColumn('document_type');
            $table->dropForeign(['uploaded_by_id']);
            $table->dropColumn('uploaded_by_id');
        });
    }
};
