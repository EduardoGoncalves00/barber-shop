<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return User::find($id);
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $barber = User::findOrFail($data['id']);
        return $barber->update($data);
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email): mixed
    {
        return User::where('email', $email)->first();
    }
}