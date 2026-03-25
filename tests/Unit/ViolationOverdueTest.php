<?php

namespace Tests\Unit;

use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViolationOverdueTest extends TestCase
{
    use RefreshDatabase;

    public function test_overdue_scope_uses_date_of_violation(): void
    {
        $operator = \App\Models\User::factory()->operator()->create();
        $violator = \App\Models\Violator::factory()->create();
        $type     = \App\Models\ViolationType::factory()->create();

        // Pending violation older than 72 hours → overdue
        $overdue = Violation::create([
            'violator_id'       => $violator->id,
            'violation_type_id' => $type->id,
            'recorded_by'       => $operator->id,
            'date_of_violation' => now()->subDays(4)->toDateString(),
            'status'            => 'pending',
        ]);

        // Pending violation within 72 hours → NOT overdue
        $fresh = Violation::create([
            'violator_id'       => $violator->id,
            'violation_type_id' => $type->id,
            'recorded_by'       => $operator->id,
            'date_of_violation' => now()->toDateString(),
            'status'            => 'pending',
        ]);

        // Settled violation older than 72 hours → NOT overdue (wrong status)
        $settled = Violation::create([
            'violator_id'       => $violator->id,
            'violation_type_id' => $type->id,
            'recorded_by'       => $operator->id,
            'date_of_violation' => now()->subDays(4)->toDateString(),
            'status'            => 'settled',
        ]);

        $overdueResults = Violation::overdue()->get();

        $this->assertCount(1, $overdueResults);
        $this->assertEquals($overdue->id, $overdueResults->first()->id);
    }

    public function test_is_overdue_accessor_uses_date_of_violation(): void
    {
        $operator = \App\Models\User::factory()->operator()->create();
        $violator = \App\Models\Violator::factory()->create();
        $type     = \App\Models\ViolationType::factory()->create();

        $v = Violation::create([
            'violator_id'       => $violator->id,
            'violation_type_id' => $type->id,
            'recorded_by'       => $operator->id,
            'date_of_violation' => now()->subDays(4)->toDateString(),
            'status'            => 'pending',
        ]);

        $this->assertTrue($v->isOverdue());
    }

    public function test_pending_active_scope_uses_date_of_violation(): void
    {
        $operator = \App\Models\User::factory()->operator()->create();
        $violator = \App\Models\Violator::factory()->create();
        $type     = \App\Models\ViolationType::factory()->create();

        $fresh = Violation::create([
            'violator_id'       => $violator->id,
            'violation_type_id' => $type->id,
            'recorded_by'       => $operator->id,
            'date_of_violation' => now()->toDateString(),
            'status'            => 'pending',
        ]);

        $this->assertCount(1, Violation::pendingActive()->get());
        $this->assertCount(0, Violation::overdue()->get());
    }
}
