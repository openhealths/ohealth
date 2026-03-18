<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CarePlanActivity;

class CarePlanActivityRepository
{
    public function getByCarePlanId(int $carePlanId)
    {
        return CarePlanActivity::where('care_plan_id', $carePlanId)->get();
    }

    public function create(array $data): CarePlanActivity
    {
        return CarePlanActivity::create($data);
    }

    public function update(CarePlanActivity $activity, array $data): bool
    {
        return $activity->update($data);
    }
}
