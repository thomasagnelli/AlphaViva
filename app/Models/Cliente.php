<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\User;

class Cliente extends User
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpf',
        'cnpj',
        'phone',
        'userId',
        'createdBy',
        'updatedBy'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }    
}
