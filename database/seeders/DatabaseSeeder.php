<?php

declare(strict_types=1);

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isLocal()) {
            // Populates following tables legal_entities, users and model has roles with test data
            $this->call(TestUserMigrate::class);
        }

        $this->fixAllPostgresSequences();
    }

    /**
     * Fixes PostgreSQL sequences for all relevant tables.
     *
     * @return void
     */
    protected function fixAllPostgresSequences(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        $tables = [
            'users',
            'legal_entities',
            'parties',
            'employees',
            'employee_requests',
            'revisions',

        ];

        foreach ($tables as $tableName) {
            $this->fixPostgresSequence($tableName);
        }
    }

    /**
     * Resets the sequence for a given table to the current maximum ID.
     * This version is more robust and handles empty tables correctly.
     *
     * @param  string  $tableName
     * @return void
     */
    protected function fixPostgresSequence(string $tableName): void
    {
        try {
            DB::statement(
                "SELECT setval(pg_get_serial_sequence('{$tableName}', 'id'), COALESCE((SELECT MAX(id) FROM \"{$tableName}\"), 0));"
            );
        } catch (Exception $e) {
            Log::warning("Could not reset sequence for table '{$tableName}': " . $e->getMessage());
        }
    }
}
