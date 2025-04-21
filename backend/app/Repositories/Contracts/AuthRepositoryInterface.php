<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email);
    public function findById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);

    public function updatePassword(User $user, string $newPassword);
}
