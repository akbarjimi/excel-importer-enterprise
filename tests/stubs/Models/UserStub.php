<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStub extends Model
{
    use HasFactory;

    protected $table = 'users_stub';

    protected $fillable = [
        'name',
        'email',
        'age',
    ];
}
