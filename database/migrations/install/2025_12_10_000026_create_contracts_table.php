<?php

declare(strict_types=1);

use App\Enums\JobStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', static function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Local relations
            $table->foreignId('legal_entity_id')->constrained('legal_entities');

            // API fields
            $table->uuid('contractor_legal_entity_id');
            $table->uuid('contractor_owner_id');
            $table->string('contractor_base')->nullable();
            $table->jsonb('contractor_payment_details')->nullable();
            $table->string('contractor_rmsp_amount')->nullable();

            $table->boolean('external_contractor_flag')->default(false);
            $table->jsonb('external_contractors')->nullable();
            $table->jsonb('contractor_employee_divisions')->nullable();
            $table->jsonb('contractor_divisions')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->uuid('nhs_legal_entity_id')->nullable();
            $table->uuid('nhs_signer_id')->nullable();
            $table->string('nhs_signer_base')->nullable();
            $table->string('issue_city')->nullable();
            $table->double('nhs_contract_price')->nullable();
            $table->string('contract_number')->nullable();

            $table->uuid('contract_id')->nullable();
            $table->uuid('assignee_id')->nullable();
            $table->string('status');

            // Sync Status
            $table->enum('sync_status', JobStatus::values())
                ->default(JobStatus::COMPLETED->value)
                ->nullable();

            $table->string('status_reason')->nullable();
            $table->string('nhs_payment_method')->nullable();
            $table->date('nhs_signed_date')->nullable();

            $table->uuid('previous_request_id')->nullable();
            $table->string('id_form')->nullable();
            $table->string('type')->nullable();

            $table->boolean('contractor_signed')->default(false);
            $table->text('misc')->nullable();

            // Files references
            $table->string('statute_md5')->nullable();
            $table->string('additional_document_md5')->nullable();

            // JSON Data
            $table->jsonb('data')->nullable();
            $table->jsonb('medical_programs')->nullable();

            // Metadata
            $table->uuid('inserted_by')->nullable();
            $table->timestamp('inserted_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Local timestamp
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
