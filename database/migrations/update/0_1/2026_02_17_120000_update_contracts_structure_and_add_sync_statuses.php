<?php

declare(strict_types=1);

use App\Enums\JobStatus;
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
        // 1. Updating Table LEGAL_ENTITIES (Sync Statuses)
        Schema::table('legal_entities', function (Blueprint $table) {
            if (!Schema::hasColumn('legal_entities', 'contract_sync_status')) {
                $table->enum('contract_sync_status', JobStatus::values())
                    ->nullable()
                    ->after('equipment_sync_status');
            }

            if (!Schema::hasColumn('legal_entities', 'contract_request_sync_status')) {
                $table->enum('contract_request_sync_status', JobStatus::values())
                    ->nullable()
                    ->after('contract_sync_status');
            }
        });

        // 2. Updating the CONTRACTS table
        Schema::table('contracts', function (Blueprint $table) {
            // Add synchronization status
            if (!Schema::hasColumn('contracts', 'sync_status')) {
                $table->enum('sync_status', JobStatus::values())
                    ->default(JobStatus::COMPLETED->value)
                    ->nullable()
                    ->after('status');
            }

            // Fixing the data type (previously integer, now string for flexibility)
            if (Schema::hasColumn('contracts', 'contractor_rmsp_amount')) {
                $table->string('contractor_rmsp_amount')->nullable()->change();
            } else {
                $table->string('contractor_rmsp_amount')->nullable();
            }
        });

        // 3. Table CONTRACT_REQUESTS Update (Most Changes)
        Schema::table('contract_requests', function (Blueprint $table) {

            // Add synchronization status
            if (!Schema::hasColumn('contract_requests', 'sync_status')) {
                $table->enum('sync_status', JobStatus::values())
                    ->default(JobStatus::COMPLETED->value)
                    ->nullable()
                    ->after('status');
            }

            // Add fields that are needed to display parts (Partials),
            // but which were not in the "new" installation migration

            if (!Schema::hasColumn('contract_requests', 'contractor_divisions')) {
                $table->jsonb('contractor_divisions')->nullable();
            }

            if (!Schema::hasColumn('contract_requests', 'medical_programs')) {
                $table->jsonb('medical_programs')->nullable();
            }

            if (!Schema::hasColumn('contract_requests', 'issue_city')) {
                $table->string('issue_city')->nullable();
            }

            if (!Schema::hasColumn('contract_requests', 'contractor_rmsp_amount')) {
                $table->string('contractor_rmsp_amount')->nullable();
            }

            if (!Schema::hasColumn('contract_requests', 'printout_content')) {
                $table->text('printout_content')->nullable();
            }

            if (!Schema::hasColumn('contract_requests', 'assignee_id')) {
                $table->uuid('assignee_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Roll back changes (delete added columns)
        Schema::table('legal_entities', function (Blueprint $table) {
            $table->dropColumn(['contract_sync_status', 'contract_request_sync_status']);
        });

        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'sync_status')) {
                $table->dropColumn('sync_status');
            }
        });

        Schema::table('contract_requests', function (Blueprint $table) {
            $columnsToDrop = [
                'sync_status',
                'contractor_divisions',
                'medical_programs',
                'issue_city',
                'contractor_rmsp_amount',
                'printout_content',
                'assignee_id'
            ];

            // We delete only those that exist
            $existingColumns = [];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('contract_requests', $col)) {
                    $existingColumns[] = $col;
                }
            }

            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
