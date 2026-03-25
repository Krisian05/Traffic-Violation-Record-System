<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Violation;
use App\Models\Violator;
use App\Models\ViolationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ViolationPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeViolation(User $recorder): Violation
    {
        return Violation::create([
            'violator_id'       => Violator::factory()->create()->id,
            'violation_type_id' => ViolationType::factory()->create()->id,
            'recorded_by'       => $recorder->id,
            'date_of_violation' => now()->toDateString(),
            'status'            => 'pending',
        ]);
    }

    // ── Operator tests ────────────────────────────────────────────────────────

    public function test_operator_can_view_any_violation(): void
    {
        $operator  = User::factory()->operator()->create();
        $violation = $this->makeViolation($operator);

        $response = $this->actingAs($operator)->get(route('violations.show', $violation));
        $response->assertOk();
    }

    public function test_operator_can_edit_any_violation(): void
    {
        $operator  = User::factory()->operator()->create();
        $violation = $this->makeViolation($operator);

        $response = $this->actingAs($operator)->get(route('violations.edit', $violation));
        $response->assertOk();
    }

    public function test_operator_can_edit_violation_recorded_by_another_operator(): void
    {
        $operatorA = User::factory()->operator()->create();
        $operatorB = User::factory()->operator()->create();
        $violation = $this->makeViolation($operatorA);

        // Operator B can still edit a violation recorded by Operator A
        $response = $this->actingAs($operatorB)->get(route('violations.edit', $violation));
        $response->assertOk();
    }

    // ── Traffic officer tests ─────────────────────────────────────────────────

    public function test_officer_can_edit_own_violation(): void
    {
        $officer   = User::factory()->trafficOfficer()->create();
        $violation = $this->makeViolation($officer);

        $response = $this->actingAs($officer)->get(route('officer.violations.edit', $violation));
        $response->assertOk();
    }

    public function test_officer_cannot_edit_another_officers_violation(): void
    {
        $officerA  = User::factory()->trafficOfficer()->create();
        $officerB  = User::factory()->trafficOfficer()->create();
        $violation = $this->makeViolation($officerA);

        // Officer B cannot edit a violation recorded by Officer A
        $response = $this->actingAs($officerB)->get(route('officer.violations.edit', $violation));
        $response->assertForbidden();
    }

    public function test_officer_cannot_access_operator_edit_route(): void
    {
        $officer   = User::factory()->trafficOfficer()->create();
        $violation = $this->makeViolation($officer);

        // Officer tries operator portal route — blocked by role middleware
        $response = $this->actingAs($officer)->get(route('violations.edit', $violation));
        $response->assertForbidden();
    }

    // ── Delete / settle ───────────────────────────────────────────────────────

    public function test_only_operator_can_delete_violation(): void
    {
        $operator = User::factory()->operator()->create();
        $officer  = User::factory()->trafficOfficer()->create();
        $violation = $this->makeViolation($operator);

        // Officer cannot delete (role middleware blocks before policy)
        $this->actingAs($officer)
             ->delete(route('violations.destroy', $violation))
             ->assertForbidden();

        // Operator can delete
        $this->actingAs($operator)
             ->delete(route('violations.destroy', $violation))
             ->assertRedirect();

        $this->assertSoftDeleted('violations', ['id' => $violation->id]);
    }
}
