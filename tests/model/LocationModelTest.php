<?php

namespace Tests\Unit;

use App\Models\LocationModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Unit Test: LocationModel
 *
 * Tests the model-layer logic for the Location entity:
 * - Validation rules (required fields, max length)
 * - Data integrity on save and retrieval
 */
class LocationModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $seed    = 'LocationSeeder';

    protected LocationModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new LocationModel();
    }

    // -----------------------------------------------------------------------
    // Test 1 — Validation: required fields (name & address) must be present
    // -----------------------------------------------------------------------

    /**
     * @test
     * Inserting a location with empty name and address must fail validation.
     */
    public function test_validation_fails_when_name_and_address_are_empty(): void
    {
        $data = [
            'name'    => '',
            'address' => '',
        ];

        $result = $this->model->insert($data);

        $this->assertFalse($result, 'Insert should fail when required fields are empty.');

        $errors = $this->model->errors();
        $this->assertNotEmpty($errors);

        // Optionally assert specific field errors exist
        $this->assertArrayHasKey('name', $errors);
    }

    // -----------------------------------------------------------------------
    // Test 2 — Validation: name must not exceed max_length
    // -----------------------------------------------------------------------

    /**
     * @test
     * If the model defines a max_length rule on 'name', a string that is too
     * long should fail validation before hitting the database.
     */
    public function test_validation_fails_when_name_exceeds_max_length(): void
    {
        $data = [
            'name'    => str_repeat('A', 300),  // 300 chars — way over any sane limit
            'address' => 'Jl. Sudirman No. 1, Jakarta',
        ];

        $result = $this->model->insert($data);

        // Either validation rejects it (false) or the DB throws — either is acceptable.
        // Here we specifically test the model's own validation layer.
        $this->assertFalse($result, 'Insert should fail when name is too long.');
    }

    // -----------------------------------------------------------------------
    // Test 3 — CRUD: update() persists changes correctly
    // -----------------------------------------------------------------------

    /**
     * @test
     * After updating a location's address, find() must return the new value.
     */
    public function test_update_persists_new_address(): void
    {
        $id = $this->model->insert([
            'name'    => 'Garden Venue',
            'address' => 'Old Address 1',
        ]);

        $this->model->update($id, ['address' => 'New Address 99']);

        $location = $this->model->find($id);
        $this->assertSame('New Address 99', $location['address']);
    }

    // -----------------------------------------------------------------------
    // Test 4 — CRUD: findAll() returns all seeded / inserted records
    // -----------------------------------------------------------------------

    /**
     * @test
     * findAll() must return at least the records we just inserted.
     */
    public function test_findAll_returns_multiple_records(): void
    {
        $this->model->insert(['name' => 'Venue Alpha', 'address' => 'Addr A']);
        $this->model->insert(['name' => 'Venue Beta',  'address' => 'Addr B']);

        $all = $this->model->findAll();

        $this->assertIsArray($all);
        $this->assertGreaterThanOrEqual(2, count($all));
    }
}
