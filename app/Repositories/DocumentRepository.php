<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Relations\Document;
use Illuminate\Database\Eloquent\Model;

class DocumentRepository
{
    /**
     * Sync documents data to the current ($documents) state.
     * If $documents is empty, the existing data will be deleted.
     *
     * @param  Model  $model
     * @param  array  $documents
     * @return void
     */
    public function sync(Model $model, array $documents): void
    {
        // Remove all documents records belongs to the $model
        Document::where([
            'documentable_type' => get_class($model),
            'documentable_id' => $model->id
        ])
            ->delete();

        if (empty($documents)) {
            return;
        }

        foreach ($documents as $documentData) {
            $document = Document::updateOrCreate(
                [
                'documentable_type' => get_class($model),
                'documentable_id' => $model->id,
                'number' => $documentData['number']
            ],
                $documentData
            );

            $model->documents()->save($document);
        }
    }
}
