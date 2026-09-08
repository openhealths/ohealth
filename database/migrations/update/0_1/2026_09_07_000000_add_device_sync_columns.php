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
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('legal_entities', static function (Blueprint $table) {
            if (!Schema::hasColumn('legal_entities', 'device_sync_status')) {
                $table->enum('device_sync_status', JobStatus::values())
                    ->nullable()
                    ->after('procedure_sync_status');
            }
        });

        Schema::table('devices', static function (Blueprint $table) {
            if (!Schema::hasColumn('devices', 'ehealth_inserted_at')) {
                $table->timestamp('ehealth_inserted_at')->nullable()->after('parent_id');
            }

            if (!Schema::hasColumn('devices', 'ehealth_updated_at')) {
                $table->timestamp('ehealth_updated_at')->nullable()->after('ehealth_inserted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('legal_entities', static function (Blueprint $table) {
            if (Schema::hasColumn('legal_entities', 'device_sync_status')) {
                $table->dropColumn('device_sync_status');
            }
        });

        Schema::table('devices', static function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'ehealth_inserted_at')) {
                $table->dropColumn('ehealth_inserted_at');
            }

            if (Schema::hasColumn('devices', 'ehealth_updated_at')) {
                $table->dropColumn('ehealth_updated_at');
            }
        });
    }
};
