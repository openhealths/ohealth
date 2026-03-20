<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee\Employee;
use App\Models\User;
use App\Models\Party;
use App\Models\Division;
use App\Enums\User\Role;

echo "Creating dummy User, Party, Employee for PR51 compat test...\n";

// Ensure DB in testing
try {
    DB::beginTransaction();

    $party = Party::factory()->create();
    $user = User::factory()->create(['party_id' => $party->id]);
    
    $division = Division::first();
    if (!$division) {
        $division = Division::factory()->create();
    }

    $employee = Employee::factory()->create([
        'party_id' => $party->id,
        'division_id' => $division->id,
        'employee_type' => Role::DOCTOR->value,
    ]);

    echo "User ID: {$user->id}, Employee ID: {$employee->id}\n";
    echo "Employee Users Before: " . $employee->users()->count() . "\n";

    // Call private method
    $processor = new \App\Services\Employee\EmployeeRequestProcessor();
    $reflection = new \ReflectionMethod(get_class($processor), 'assignUserRoles');
    $reflection->setAccessible(true);
    
    $reflection->invokeArgs($processor, [$employee, 1]); // 1 is fake legalEntityId
    
    echo "Employee Users After: " . $employee->users()->count() . "\n";
    
    // Verify user role
    echo "Has Doctor Role: " . ($user->hasRole(Role::DOCTOR->value) ? 'Yes' : 'No') . "\n";

    DB::rollBack();
    echo "Test finished successfully.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
