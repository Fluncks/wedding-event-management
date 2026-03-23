<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\EventModel;
use App\Models\LocationModel;
use App\Models\UserModel;

/**
 * POST actions for events, locations, and admin user creation.
 */
class CrudController extends BaseController
{
    private function locationUploadPath(): string
    {
        $dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR;
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    private function deleteLocationImageFile(?string $filename): void
    {
        if ($filename === null || $filename === '') {
            return;
        }
        $path = $this->locationUploadPath() . $filename;
        if (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * Store uploaded image; returns new filename or null if no file / skipped.
     */
    private function storeLocationImage(): ?string
    {
        $file = $this->request->getFile('image');
        if (! $file || ! $file->isValid()) {
            return null;
        }
        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowed, true)) {
            return '__invalid__';
        }
        if ($file->getSize() > 3 * 1024 * 1024) {
            return '__toobig__';
        }

        $name = $file->getRandomName();
        $file->move($this->locationUploadPath(), $name);

        return $name;
    }

    public function saveEvent()
    {
        $session = session();
        $userId  = (int) $session->get('user_id');

        $eventId = (int) $this->request->getPost('event_id');

        $clientModel = model(ClientModel::class);
        $eventModel  = model(EventModel::class);

        $bride = trim((string) $this->request->getPost('bride_name'));
        $groom = trim((string) $this->request->getPost('groom_name'));
        $phone = (string) $this->request->getPost('client_phone');

        $payload = [
            'name'        => (string) $this->request->getPost('name'),
            'event_type'  => (string) $this->request->getPost('event_type'),
            'event_date'  => (string) $this->request->getPost('event_date'),
            'event_time'  => (string) $this->request->getPost('event_time'),
            'location_id' => (int) $this->request->getPost('location_id'),
            'guests'      => (int) $this->request->getPost('guests'),
            'budget'      => (int) $this->request->getPost('budget'),
            'description' => (string) $this->request->getPost('description'),
            'status'      => (string) $this->request->getPost('status'),
            'created_by'  => $userId,
        ];

        if ($payload['name'] === '' || $payload['event_type'] === '' || $payload['event_date'] === '' || $payload['location_id'] < 1) {
            return redirect()->back()->withInput()->with('error', 'Please fill required event fields.');
        }
        if ($bride === '' || $groom === '') {
            return redirect()->back()->withInput()->with('error', 'Bride and groom names are required.');
        }

        if ($eventId > 0) {
            $existing = $eventModel->find($eventId);
            if (! $existing) {
                return redirect()->to('/event-list')->with('error', 'Event not found.');
            }

            $clientId = (int) $existing['client_id'];
            $clientModel->update($clientId, [
                'bride_name' => $bride,
                'groom_name' => $groom,
                'phone'      => $phone,
            ]);

            $eventModel->update($eventId, [
                'name'        => $payload['name'],
                'client_id'   => $clientId,
                'location_id' => $payload['location_id'],
                'event_type'  => $payload['event_type'],
                'event_date'  => $payload['event_date'],
                'event_time'  => $payload['event_time'] !== '' ? $payload['event_time'] : null,
                'guests'      => $payload['guests'],
                'budget'      => $payload['budget'],
                'description' => $payload['description'],
                'status'      => $payload['status'],
            ]);

            return redirect()->to('/event-list')->with('message', 'Event updated.');
        }

        $clientModel->insert([
            'bride_name' => $bride,
            'groom_name' => $groom,
            'phone'      => $phone,
        ]);
        $clientId = (int) $clientModel->getInsertID();

        if ($clientId < 1) {
            return redirect()->back()->withInput()->with('error', 'Could not save client.');
        }

        $eventModel->insert([
            'name'        => $payload['name'],
            'client_id'   => $clientId,
            'location_id' => $payload['location_id'],
            'event_type'  => $payload['event_type'],
            'event_date'  => $payload['event_date'],
            'event_time'  => $payload['event_time'] !== '' ? $payload['event_time'] : null,
            'guests'      => $payload['guests'],
            'budget'      => $payload['budget'],
            'description' => $payload['description'],
            'status'      => $payload['status'],
            'created_by'  => $userId,
        ]);

        return redirect()->to('/event-list')->with('message', 'Event created.');
    }

    public function deleteEvent(int $id)
    {
        $eventModel = model(EventModel::class);
        $row        = $eventModel->find($id);
        if (! $row) {
            return redirect()->to('/event-list')->with('error', 'Event not found.');
        }

        $clientId = (int) $row['client_id'];
        $eventModel->delete($id);

        $other = model(EventModel::class)->where('client_id', $clientId)->countAllResults();
        if ($other === 0) {
            model(ClientModel::class)->delete($clientId);
        }

        return redirect()->to('/event-list')->with('message', 'Event deleted.');
    }

    public function saveLocation()
    {
        $locationModel = model(LocationModel::class);

        $id = (int) $this->request->getPost('location_id');

        $data = [
            'name'          => (string) $this->request->getPost('name'),
            'address'       => (string) $this->request->getPost('address'),
            'capacity'      => (int) $this->request->getPost('capacity'),
            'type'          => (string) $this->request->getPost('type'),
            'facilities'    => (string) $this->request->getPost('facilities'),
            'contact_phone' => (string) $this->request->getPost('contact_phone'),
            'description'   => (string) $this->request->getPost('description'),
        ];

        if ($data['name'] === '' || $data['address'] === '' || $data['type'] === '') {
            return redirect()->back()->withInput()->with('error', 'Please fill required location fields.');
        }

        $existing = $id > 0 ? $locationModel->find($id) : null;

        $newImage = $this->storeLocationImage();
        if ($newImage === '__invalid__') {
            return redirect()->back()->withInput()->with('error', 'Image must be JPG, PNG, GIF, or WebP.');
        }
        if ($newImage === '__toobig__') {
            return redirect()->back()->withInput()->with('error', 'Image must be 3MB or smaller.');
        }

        if ($newImage !== null) {
            if ($existing && ! empty($existing['image'])) {
                $this->deleteLocationImageFile($existing['image']);
            }
            $data['image'] = $newImage;
        }

        if ($id > 0) {
            if (! $existing) {
                return redirect()->to('/location-list')->with('error', 'Location not found.');
            }
            if ($newImage === null) {
                unset($data['image']);
            }
            $locationModel->update($id, $data);

            return redirect()->to('/location-list')->with('message', 'Location updated.');
        }

        $locationModel->insert($data);

        return redirect()->to('/location-list')->with('message', 'Location created.');
    }

    public function deleteLocation(int $id)
    {
        $locationModel = model(LocationModel::class);
        $row           = $locationModel->find($id);
        if (! $row) {
            return redirect()->to('/location-list')->with('error', 'Location not found.');
        }

        $inUse = model(EventModel::class)->where('location_id', $id)->countAllResults();
        if ($inUse > 0) {
            return redirect()->to('/location-list')->with('error', 'Cannot delete: location is used by events.');
        }

        $this->deleteLocationImageFile($row['image'] ?? null);
        $locationModel->delete($id);

        return redirect()->to('/location-list')->with('message', 'Location deleted.');
    }

    public function createUser()
    {
        $name     = (string) $this->request->getPost('name');
        $email    = (string) $this->request->getPost('email');
        $phone    = (string) $this->request->getPost('phone');
        $role     = (string) $this->request->getPost('role');
        $password = (string) $this->request->getPost('password');
        $confirm  = (string) $this->request->getPost('confirm_password');

        if ($name === '' || $email === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Please fill required fields.');
        }
        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }
        if (! in_array($role, ['admin', 'staff'], true)) {
            return redirect()->back()->withInput()->with('error', 'Invalid role.');
        }

        $userModel = model(UserModel::class);
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Email already registered.');
        }

        $userModel->insert([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'role'     => $role,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/create-account')->with('message', 'Account created.');
    }

    public function deleteUser(int $id)
    {
        $session = session();
        if ((int) $session->get('user_id') === $id) {
            return redirect()->to('/admin/create-account')->with('error', 'You cannot delete your own account.');
        }

        $userModel = model(UserModel::class);
        $user      = $userModel->find($id);
        if (! $user) {
            return redirect()->to('/admin/create-account')->with('error', 'User not found.');
        }

        $userModel->delete($id);

        return redirect()->to('/admin/create-account')->with('message', 'User removed.');
    }
}
