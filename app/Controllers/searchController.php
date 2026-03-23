<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\LocationModel;

/**
 * List pages with optional search (?q=).
 */
class SearchController extends BaseController
{
    public function events()
    {
        $q = $this->request->getGet('q');
        $q = is_string($q) ? trim($q) : '';

        $events = model(EventModel::class)->listWithRelations($q !== '' ? $q : null);

        return $this->render('event-list', [
            'title'   => 'Event List',
            'active'  => 'events',
            'search'  => $q,
            'events'  => $events,
        ]);
    }

    public function locations()
    {
        $q = $this->request->getGet('q');
        $q = is_string($q) ? trim($q) : '';

        $locationModel = model(LocationModel::class);
        $builder       = $locationModel->orderBy('name', 'ASC');
        if ($q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('address', $q)
                ->orLike('type', $q)
                ->groupEnd();
        }
        $locations = $builder->findAll();

        return $this->render('location-list', [
            'title'     => 'Location List',
            'active'    => 'locations',
            'search'    => $q,
            'locations' => $locations,
        ]);
    }
}
