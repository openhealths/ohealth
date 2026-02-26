<?php

declare(strict_types=1);

use App\Enums\Employee\RevisionStatus;
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
        Schema::create('revisions', static function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->jsonb('ehealth_response')->nullable();
            $table->enum('status', array_column(RevisionStatus::cases(), 'value'))->default(RevisionStatus::PENDING->value);
            $table->morphs('revisionable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
