<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GuestRepository
{
    public function all()
    {
        return DB::select('SELECT * FROM guests');
    }

    public function find($id)
    {
        $result = DB::select('SELECT * FROM guests WHERE id = ?', [$id]);
        if (!$result) {
            return null;
        }
    

        return (array) $result[0];
    }

    public function create(array $data)
    {
        DB::insert(
            'INSERT INTO guests (first_name, last_name, email, phone, country, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $data['first_name'],
                $data['last_name'],
                $data['email'] ?? null,
                $data['phone'],
                $data['country'] ?? null,
                now(),
                now(),
            ]
        );
    }

    public function update($id, array $data)
    {
        $current = $this->find($id);
        if (!$current) {
            return false;
        }
    
        return DB::update(
            'UPDATE guests SET first_name = ?, last_name = ?, email = ?, phone = ?, country = ?, updated_at = ? WHERE id = ?',
            [
                $data['first_name'] ?? $current['first_name'],
                $data['last_name'] ?? $current['last_name'],
                $data['email'] ?? $current['email'],
                $data['phone'] ?? $current['phone'],
                $data['country'] ?? $current['country'],
                now(),
                $id,
            ]
        ) > 0;
    }

    public function delete($id)
    {
        return DB::delete('DELETE FROM guests WHERE id = ?', [$id]) > 0;
    }
}