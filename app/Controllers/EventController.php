<?php

namespace App\Controllers;

class EventController extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = model('EventModel');
    }

    public function index()
    {
        $search = $this->request->getGet('q') ?? '';
        $events = $this->eventModel->listWithRelations($search);

        return view('event-list', [
            'events' => $events,
            'search' => $search,
        ]);
    }

    public function save()
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'event_date'  => $this->request->getPost('event_date'),
            'location_id' => $this->request->getPost('location_id'),
            'event_type'  => $this->request->getPost('event_type') ?? 'ceremony',
            'client_id'   => $this->request->getPost('client_id') ?? 1,
        ];

        if ($this->eventModel->insert($data)) {
            return redirect()->to('events')->with('success', 'Event created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->eventModel->errors());
    }

    public function delete($id)
    {
        $this->eventModel->delete($id);
        return redirect()->to('events')->with('success', 'Event deleted successfully');
    }
}
