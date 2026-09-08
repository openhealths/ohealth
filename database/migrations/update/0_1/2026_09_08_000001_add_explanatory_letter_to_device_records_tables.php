<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Keep the reason a device association or a detected issue was marked as entered in error, the same way
     * the other records of an encounter package already do.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('device_associations')) {
            Schema::table('device_associations', static function (Blueprint $table) {
                if (!Schema::hasColumn('device_associations', 'explanatory_letter')) {
                    $table->string('explanatory_letter')
                        ->nullable()
                        ->after('status')
                        ->comment('Reason the association was marked as entered in error');
                }
            });
        }

        if (Schema::hasTable('detected_issues')) {
            Schema::table('detected_issues', static function (Blueprint $table) {
                if (!Schema::hasColumn('detected_issues', 'explanatory_letter')) {
                    $table->string('explanatory_letter')
                        ->nullable()
                        ->after('status')
                        ->comment('Reason the detected issue was marked as entered in error');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('device_associations')) {
            Schema::table('device_associations', static function (Blueprint $table) {
                if (Schema::hasColumn('device_associations', 'explanatory_letter')) {
                    $table->dropColumn('explanatory_letter');
                }
            });
        }

        if (Schema::hasTable('detected_issues')) {
            Schema::table('detected_issues', static function (Blueprint $table) {
                if (Schema::hasColumn('detected_issues', 'explanatory_letter')) {
                    $table->dropColumn('explanatory_letter');
                }
            });
        }
    }
};
