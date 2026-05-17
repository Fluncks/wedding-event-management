<?php

namespace App\Controllers;

class LocationController extends BaseController
{
    protected $locationModel;

    public function __construct()
    {
        $this->locationModel = model('LocationModel');
    }

    public function index()
    {
        $search = $this->request->getGet('q') ?? '';
        $locations = $this->locationModel->findAll();

        return view('location-list', [
            'locations' => $locations,
            'search'    => $search,
        ]);
    }

    public function save()
    {
        $data = [
            'name'    => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'type'    => $this->request->getPost('type') ?? '',
            'capacity' => $this->request->getPost('capacity') ?? 0,
        ];

        if ($this->locationModel->insert($data)) {
            return redirect()->to('locations')->with('success', 'Location created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->locationModel->errors());
    }

    public function delete($id)
    {
        $this->locationModel->delete($id);
        return redirect()->to('locations')->with('success', 'Location deleted successfully');
    }
}
