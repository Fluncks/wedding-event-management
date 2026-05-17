<?php

namespace Tests\Unit;

use App\Models\EventModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Unit Test: EventModel
 *
 * Tests the model-layer logic for the Event entity:
 * - Validation rules (required fields, data types)
 * - Allowed fields whitelist
 */
class EventModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // Refresh DB after every test so tests don't bleed into each other
    protected $refresh = true;

    // Seed the test DB with dummy data before each test
    protected $seed = 'EventSeeder';

    protected EventModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new EventModel();
    }

    // -----------------------------------------------------------------------
    // Test 1 — Validation: required fields must not be empty
    // -----------------------------------------------------------------------

    /**
     * @test
     * Inserting a completely empty record should fail validation.
     * The model must reject it and return false from insert().
     */
    public function test_validation_fails_when_required_fields_are_empty(): void
    {
        $data = [
            'name'        => '',
            'event_date'  => '',
            'location_id' => '',
        ];

        $result = $this->model->insert($data);

        // insert() returns false when validation fails
        $this->assertFalse($result);

        // Model should have populated the errors bag
        $errors = $this->model->errors();
        $this->assertNotEmpty($errors, 'Expected validation errors but got none.');
    }

    // -----------------------------------------------------------------------
    // Test 2 — Allowed Fields: unknown fields must be stripped before save
    // -----------------------------------------------------------------------

    /**
     * @test
     * Any field NOT listed in $allowedFields must be silently stripped
     * before the SQL INSERT, preventing mass-assignment vulnerabilities.
     * The insert should still succeed using only the valid fields.
     */
    public function test_allowed_fields_strips_unknown_columns(): void
    {
        $data = [
            'name'            => 'Test Wedding',
            'client_id'       => 1,
            'event_date'      => '2025-12-25',
            'location_id'     => 1,
            'hacked_column'   => 'malicious_value',  // NOT in $allowedFields
            'another_unknown' => 'should_be_ignored',
        ];

        $id = $this->model->insert($data);

        // Insert must succeed (returns a positive integer ID)
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        // Fetch the saved row and confirm it has no trace of unknown fields
        $event = $this->model->find($id);
        $this->assertArrayNotHasKey('hacked_column', (array) $event);
        $this->assertArrayNotHasKey('another_unknown', (array) $event);

        // But the real data must be saved correctly
        $this->assertSame('Test Wedding', $event['name']);
    }

    // -----------------------------------------------------------------------
    // Test 3 — CRUD: find() returns the correct record
    // -----------------------------------------------------------------------

    /**
     * @test
     * After inserting a record, find() by its ID must return the same data.
     */
    public function test_find_returns_correct_event(): void
    {
        $data = [
            'name'        => 'Grand Ballroom Ceremony',
            'client_id'   => 1,
            'event_date'  => '2025-06-15',
            'location_id' => 1,
        ];

        $id    = $this->model->insert($data);
        $event = $this->model->find($id);

        $this->assertNotNull($event);
        $this->assertSame('Grand Ballroom Ceremony', $event['name']);
        $this->assertSame('2025-06-15', $event['event_date']);
    }

    // -----------------------------------------------------------------------
    // Test 4 — CRUD: delete() removes the record
    // -----------------------------------------------------------------------

    /**
     * @test
     * After deleting a record by ID, find() must return null for that ID.
     */
    public function test_delete_removes_event(): void
    {
        $id = $this->model->insert([
            'name'        => 'Event To Delete',
            'client_id'   => 1,
            'event_date'  => '2025-03-01',
            'location_id' => 1,
        ]);

        $this->model->delete($id);

        $event = $this->model->find($id);
        $this->assertNull($event, 'Deleted event should not be findable.');
    }
}
