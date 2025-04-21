<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        $user = DB::table('users')->where('email', $email)->first();
        return $user ? $this->hydrateUser($user) : null;
    }

    public function findById($id): ?User
    {
        $user = DB::table('users')->where('id', $id)->first();
        return $user ? $this->hydrateUser($user) : null;
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $id = DB::table('users')->insertGetId($data);
        return $this->findById($id);
    }

    public function update(array $data, $id): ?User
    {
        DB::table('users')->where('id', $id)->update($data);
        return $this->findById($id);
    }

    public function delete($id): bool
    {
        return DB::table('users')->where('id', $id)->delete() > 0;
    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        return DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => Hash::make($newPassword)]) > 0;
    }

    protected function hydrateUser(object $userData): User
    {
        return User::hydrate([(array)$userData])->first();
    }
}
