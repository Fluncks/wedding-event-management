<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\EventModel;
use App\Models\LocationModel;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $db = db_connect();

        $totalEvents     = $db->table('events')->countAllResults();
        $totalLocations  = $db->table('locations')->countAllResults();
        $completedEvents = $db->table('events')->where('status', 'completed')->countAllResults();
        $pendingEvents   = $db->table('events')->where('status', 'pending')->countAllResults();

        $recentEvents = model(EventModel::class)->listWithRelations(null);
        $recentEvents = array_slice($recentEvents, 0, 5);

        $today = date('Y-m-d');
        $upcoming = $db->table('events e')
            ->select('e.*, CONCAT(c.bride_name, " & ", c.groom_name) AS client_name, l.name AS location_name')
            ->join('clients c', 'c.id = e.client_id')
            ->join('locations l', 'l.id = e.location_id')
            ->where('e.event_date >=', $today)
            ->orderBy('e.event_date', 'ASC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $popularLocations = model(LocationModel::class)->orderBy('id', 'ASC')->limit(3)->findAll();

        return $this->render('dashboard', [
            'title'             => 'Dashboard',
            'active'            => 'dashboard',
            'totalEvents'       => $totalEvents,
            'totalLocations'    => $totalLocations,
            'completedEvents'   => $completedEvents,
            'pendingEvents'     => $pendingEvents,
            'recentEvents'      => $recentEvents,
            'upcomingEvents'    => $upcoming,
            'popularLocations'  => $popularLocations,
        ]);
    }

    public function createEvent($id = null)
    {
        $locations = model(LocationModel::class)->orderBy('name', 'ASC')->findAll();
        $event     = null;
        $client    = null;

        if ($id === null && $locations === []) {
            return redirect()->to('/location-list')->with('error', 'Add at least one location before creating an event.');
        }

        if ($id !== null) {
            $event = model(EventModel::class)->find((int) $id);
            if (! $event) {
                return redirect()->to('/event-list')->with('error', 'Event not found.');
            }
            $client = model(ClientModel::class)->find((int) $event['client_id']);
        }

        return $this->render('create-event', [
            'title'     => $event ? 'Edit Event' : 'Create Event',
            'active'    => 'events',
            'locations' => $locations,
            'event'     => $event,
            'client'    => $client,
        ]);
    }

    public function createLocation($id = null)
    {
        $location = null;
        if ($id !== null) {
            $location = model(LocationModel::class)->find((int) $id);
            if (! $location) {
                return redirect()->to('/location-list')->with('error', 'Location not found.');
            }
        }

        return $this->render('create-location', [
            'title'    => $location ? 'Edit Location' : 'Create Location',
            'active'   => 'locations',
            'location' => $location,
        ]);
    }

    public function createAccount()
    {
        $users = model(UserModel::class)->orderBy('name', 'ASC')->findAll();

        return $this->render('admin/create-account', [
            'title'  => 'Create Account',
            'active' => 'staff',
            'users'  => $users,
        ]);
    }
}
