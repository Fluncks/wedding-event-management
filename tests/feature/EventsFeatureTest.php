<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Feature Test: Events Controller
 *
 * Sends real HTTP requests through the CI4 router and checks the
 * responses — exactly how a browser would interact with your app.
 *
 * Covers:
 *   1. GET  /events       → index page lists all events (200 OK)
 *   2. POST /events/save  → creates a new event, then redirects
 */
class EventsFeatureTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $seed    = 'EventSeeder';

    // -----------------------------------------------------------------------
    // Test 1 — GET /events returns 200 and shows the events list page
    // -----------------------------------------------------------------------

    /**
     * @test
     * Visiting the events index must return HTTP 200 and render content
     * that indicates we are on the events listing page.
     */
    public function test_index_returns_200_and_shows_event_list(): void
    {
        $result = $this->get('events');

        // HTTP status must be 200 OK
        $result->assertStatus(200);

        // The response body must contain a heading or keyword that proves
        // we landed on the events page (adjust text to match your actual view)
        $result->assertSee('Events');
    }

    // -----------------------------------------------------------------------
    // Test 2 — POST /events/save with valid data creates an event
    // -----------------------------------------------------------------------

    /**
     * @test
     * POSTing valid event data to the save endpoint must:
     *   - Return a redirect (302) back to the events list, OR
     *   - Return 200 if the controller redirects with refresh
     * AND the new event must appear in the database afterwards.
     */
    public function test_save_creates_new_event_and_redirects(): void
    {
        $payload = [
            'name'        => 'Feature Test Wedding',
            'event_date'  => '2025-11-20',
            'location_id' => 1,
        ];

        $result = $this->post('events/save', $payload);

        // After a successful save the controller should redirect
        $result->assertRedirect();

        // Confirm the record actually exists in the DB
        $this->seeInDatabase('events', [
            'name'       => 'Feature Test Wedding',
            'event_date' => '2025-11-20',
        ]);
    }

    // -----------------------------------------------------------------------
    // Test 3 — POST /events/save with missing name shows validation error
    // -----------------------------------------------------------------------

    /**
     * @test
     * POSTing an incomplete form (missing required 'name') must NOT create a
     * record and must send the user back to the form with an error message.
     */
    public function test_save_with_missing_name_shows_validation_error(): void
    {
        $payload = [
            'name'        => '',           // intentionally blank
            'event_date'  => '2025-11-20',
            'location_id' => 1,
        ];

        $result = $this->post('events/save', $payload);

        // Controller should re-render the form (200) or redirect back (302)
        // with validation feedback — not a 500 error
        $this->assertContains(
            $result->response()->getStatusCode(),
            [200, 302],
            'Expected 200 or 302 when validation fails, got ' . $result->response()->getStatusCode()
        );

        // The word "name" (field label) or a validation error must appear in body
        // when re-rendering the form (only relevant if status is 200)
        if ($result->response()->getStatusCode() === 200) {
            $result->assertSee('name');
        }

        // Most importantly: no record should have been saved
        $this->dontSeeInDatabase('events', ['event_date' => '2025-11-20', 'name' => '']);
    }

    // -----------------------------------------------------------------------
    // Test 4 — GET /events/delete/{id} removes the event
    // -----------------------------------------------------------------------

    /**
     * @test
     * Visiting the delete route for an existing event ID must remove the
     * record from the database and redirect back to the list.
     */
    public function test_delete_removes_event_from_database(): void
    {
        // Insert a fresh record so we control the data
        $model = model('EventModel');
        $id    = $model->insert([
            'name'        => 'Event To Be Deleted',
            'client_id'   => 1,
            'event_date'  => '2025-07-07',
            'location_id' => 1,
        ]);

        $result = $this->get("events/delete/{$id}");

        // Should redirect after deletion
        $result->assertRedirect();

        // Record must no longer exist
        $this->dontSeeInDatabase('events', ['id' => $id]);
    }
}
