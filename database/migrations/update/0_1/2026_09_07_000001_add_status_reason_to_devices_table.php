<?php

declare(strict_types=1);

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
        Schema::table('devices', static function (Blueprint $table) {
            if (!Schema::hasColumn('devices', 'explanatory_letter')) {
                $table->string('explanatory_letter')->nullable()->after('note');
            }

            if (!Schema::hasColumn('devices', 'status_reason_id')) {
                $table->foreignId('status_reason_id')
                    ->nullable()
                    ->after('report_origin_id')
                    ->constrained('codeable_concepts');
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
        Schema::table('devices', static function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'status_reason_id')) {
                $table->dropConstrainedForeignId('status_reason_id');
            }

            if (Schema::hasColumn('devices', 'explanatory_letter')) {
                $table->dropColumn('explanatory_letter');
            }
        });
    }
};
