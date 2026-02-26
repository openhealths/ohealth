<?php

declare(strict_types=1);

use App\Enums\Status;
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
        Schema::create('employee_roles', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('healthcare_service_id')->constrained();
            $table->dateTimeTz('start_date');
            $table->dateTimeTz('end_date')->nullable();
            $table->enum('status', [Status::ACTIVE, Status::INACTIVE]);
            $table->boolean('is_active');
            $table->dateTimeTz('ehealth_inserted_at');
            $table->string('ehealth_inserted_by');
            $table->dateTimeTz('ehealth_updated_at');
            $table->string('ehealth_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_roles');
    }
};
