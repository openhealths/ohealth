<?php

declare(strict_types=1);

use App\Models\LegalEntity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('legal_entity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('localized_name')->nullable();
            $table->timestamps();
        });

        try {
            $this->setData();
        } catch (Exception $error) {
            $this->down();

            throw $error;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entity_types');
    }

    /**
     * Set the initial data for the legal_entity_types table.
     *
     * @return void
     */
    protected function setData(): void
    {
        $now = now();

        $availableTypes = [
            ['name' => LegalEntity::TYPE_EMERGENCY, 'localized_name' => 'legal-entity.types.emergency'],
            ['name' => LegalEntity::TYPE_MIS, 'localized_name' => 'legal-entity.types.mis'],
            ['name' => LegalEntity::TYPE_MSP, 'localized_name' => 'legal-entity.types.msp'],
            ['name' => LegalEntity::TYPE_MSP_PHARMACY, 'localized_name' => 'legal-entity.types.msp_pharmacy'],
            ['name' => LegalEntity::TYPE_NHS, 'localized_name' => 'legal-entity.types.nhs'],
            ['name' => LegalEntity::TYPE_OUTPATIENT, 'localized_name' => 'legal-entity.types.outpatient'],
            ['name' => LegalEntity::TYPE_PHARMACY, 'localized_name' => 'legal-entity.types.pharmacy'],
            ['name' => LegalEntity::TYPE_PRIMARY_CARE, 'localized_name' => 'legal-entity.types.primary_care'],
            ['name' => LegalEntity::TYPE_MSP_LIMITED, 'localized_name' => 'legal-entity.types.msp_limited']
        ];

        foreach ($availableTypes as $typeRecord) {
            $typeRecord['created_at'] = $now;
            $typeRecord['updated_at'] = $now;
        }

        DB::table('legal_entity_types')->insert($availableTypes);
    }
};
