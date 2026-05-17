<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Feature Test: Locations Controller
 *
 * Fires real HTTP requests through the CI4 router to verify the full
 * request-response cycle for the Location feature.
 *
 * Covers:
 *   1. GET  /locations       → index page lists all locations (200 OK)
 *   2. POST /locations/save  → creates a new location, then redirects
 *   3. POST /locations/save  → fails gracefully with invalid data
 *   4. GET  /locations/delete/{id} → deletes and redirects
 */
class LocationsFeatureTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $seed    = 'LocationSeeder';

    // -----------------------------------------------------------------------
    // Test 1 — GET /locations returns 200 and shows the locations list
    // -----------------------------------------------------------------------

    /**
     * @test
     * The locations index page must be reachable and render successfully.
     */
    public function test_index_returns_200_and_shows_location_list(): void
    {
        $result = $this->get('locations');

        $result->assertStatus(200);

        // Confirm we're actually on the locations page
        // (adjust this string to match a heading/text in your locations/index view)
        $result->assertSee('Locations');
    }

    // -----------------------------------------------------------------------
    // Test 2 — POST /locations/save with valid data creates a location
    // -----------------------------------------------------------------------

    /**
     * @test
     * A valid POST to the save endpoint must persist the record and redirect.
     */
    public function test_save_creates_new_location_and_redirects(): void
    {
        $payload = [
            'name'    => 'The Grand Hall',
            'address' => 'Jl. Thamrin No. 10, Jakarta Pusat',
        ];

        $result = $this->post('locations/save', $payload);

        $result->assertRedirect();

        // Verify the row was actually written to the DB
        $this->seeInDatabase('locations', [
            'name'    => 'The Grand Hall',
            'address' => 'Jl. Thamrin No. 10, Jakarta Pusat',
        ]);
    }

    // -----------------------------------------------------------------------
    // Test 3 — POST /locations/save with missing address shows error
    // -----------------------------------------------------------------------

    /**
     * @test
     * An incomplete POST (missing 'address') must not create a location and
     * must keep the user on the form or redirect back with an error.
     */
    public function test_save_with_missing_address_shows_validation_error(): void
    {
        $payload = [
            'name'    => 'Incomplete Venue',
            'address' => '',  // intentionally blank
        ];

        $result = $this->post('locations/save', $payload);

        // Must not be a server error
        $this->assertContains(
            $result->response()->getStatusCode(),
            [200, 302],
            'Expected 200 or 302 for validation failure.'
        );

        // No record should have been saved with an empty address
        $this->dontSeeInDatabase('locations', [
            'name'    => 'Incomplete Venue',
            'address' => '',
        ]);
    }

    // -----------------------------------------------------------------------
    // Test 4 — GET /locations/delete/{id} removes the location
    // -----------------------------------------------------------------------

    /**
     * @test
     * Visiting the delete route for an existing location ID must remove the
     * record from the database and redirect back to the list.
     */
    public function test_delete_removes_location_from_database(): void
    {
        $model = model('LocationModel');
        $id    = $model->insert([
            'name'    => 'Venue To Delete',
            'address' => 'Jl. Kuningan, Jakarta Selatan',
        ]);

        $result = $this->get("locations/delete/{$id}");

        $result->assertRedirect();

        $this->dontSeeInDatabase('locations', ['id' => $id]);
    }
}
