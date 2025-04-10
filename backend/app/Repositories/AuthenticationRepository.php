<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthenticationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{

    public function findByEmail(string $email)
    {
        return DB::table('users')->where('email', $email)->first();
    }

    public function findById($id)
    {
        return DB::table('users')->where('id', $id)->first();
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $id = DB::table('users')->insertGetId($data);

        return $this->findbyId($id);
    }

    public function update(array $data, $id)
    {
        DB::table('users')->where('id', $id)->update($data);

        return $this->findbyId($id);
    }

    public function delete($id)
    {
        return DB::table('users')->where('id', $id)->delete();

    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        $updated = DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($newPassword)]);
        return $updated > 0;
    }

    public function zabba()
    {
        // TODO: Implement zabba() method.
    }
}
