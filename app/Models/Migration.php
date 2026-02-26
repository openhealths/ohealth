<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Migration extends Model
{
    protected $table = 'migrations';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'migration',
        'batch'
    ];

    protected $casts = [
        'batch' => 'integer',
    ];

    /**
     * Get the backup associated with the migration.
     *
     * @return HasOne
     */
    public function backup(): HasOne
    {
        return $this->hasOne(MigrationBackup::class, 'migration_id', 'id');
    }
}
