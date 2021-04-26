<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'roleId',
        'createdBy',
        'updatedBy'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'roleId');
    }

    public function getFillable() : array
    {
        return $this->fillable;
    }

    public function passwordCheck(string $pass) : bool
    {
        if (Hash::check($pass, $this->password)) {
            return true;
        }
        return false;
    }

    public function isHash() : bool
    {
        return strlen($this->password) >= 60;
    }

    public function passwordHash() : User
    {
        if (!$this->isHash()) {
            $this->password = Hash::make( $this->password, [
                'memory' => 1024,
                'time' => 2,
                'threads' => 2,
            ]);
        }
        return $this;
    }
}
