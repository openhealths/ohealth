<?php

declare(strict_types=1);

use App\Enums\Person\MergedPersonStatus;
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
        Schema::create('merged_persons', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('eHealth identifier of the merged person record');
            $table->foreignId('person_id')
                ->comment('Identified patient the person was merged into')
                ->constrained('persons');
            $table->uuid('merged_uuid')
                ->index()
                ->comment('eHealth identifier of the person or preperson merged into the identified patient');
            $table->foreignId('merged_person_id')
                ->nullable()
                ->comment('Identified person merged into the patient, when their record is known locally')
                ->constrained('persons');
            $table->foreignId('merged_preperson_id')
                ->nullable()
                ->comment('Preperson merged into the patient, when their record is known locally')
                ->constrained('prepersons');
            $table->enum('status', MergedPersonStatus::values());
            $table->dateTime('ehealth_inserted_at');
            $table->dateTime('ehealth_updated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merged_persons');
    }
};
