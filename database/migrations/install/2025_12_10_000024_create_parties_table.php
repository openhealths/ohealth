<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('tax_id')->nullable()->index();
            $table->boolean('no_tax_id')->nullable()->default(false);
            $table->text('about_myself')->nullable();
            $table->string('verification_status')->nullable()->comment('Overall verification status');
            $table->integer('working_experience')->nullable();
            $table->integer('declaration_count')->nullable();
            $table->integer('declaration_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
