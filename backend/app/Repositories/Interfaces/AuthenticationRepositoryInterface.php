<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthenticationRepositoryInterface
{
    public function findByEmail(string $email);
    public function findById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);

    public function updatePassword(User $user, string $newPassword);
}
