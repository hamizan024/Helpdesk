<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getTechnicians(): Collection
    {
        return User::where('role', 'technician')->get();
    }
}
