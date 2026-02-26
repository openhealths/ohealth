<?php

declare(strict_types=1);

namespace App\Models\MedicalEvents\Sql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticReportPerformer extends Model
{
    protected $table = 'diagnostic_report_performer';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'diagnostic_report_id',
        'reference_id',
        'created_at',
        'updated_at'
    ];

    public function reference(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'reference_id');
    }
}
