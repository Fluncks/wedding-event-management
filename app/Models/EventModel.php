<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'name',
        'client_id',
        'location_id',
        'event_type',
        'event_date',
        'event_time',
        'guests',
        'budget',
        'description',
        'status',
        'created_by',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function listWithRelations(?string $search = null): array
    {
        $builder = $this->db->table('events e')
            ->select('e.*, c.bride_name, c.groom_name, CONCAT(c.bride_name, " & ", c.groom_name) AS client_name, l.name AS location_name')
            ->join('clients c', 'c.id = e.client_id')
            ->join('locations l', 'l.id = e.location_id')
            ->orderBy('e.event_date', 'DESC')
            ->orderBy('e.id', 'DESC');

        if ($search !== null && $search !== '') {
            $q = '%' . $search . '%';
            $builder->groupStart()
                ->like('e.name', $q)
                ->orLike('c.bride_name', $q)
                ->orLike('c.groom_name', $q)
                ->orLike('e.event_type', $q)
                ->orLike('l.name', $q)
                ->orLike('e.status', $q)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }
}
