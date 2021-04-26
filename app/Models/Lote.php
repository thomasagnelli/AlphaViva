<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quadra;

class Lote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'quadraId',
        'createdBy',
        'updatedBy'
    ];

    public function quadra()
    {
        return $this->hasOne(Quadra::class, 'id', 'quadraId');
    }
}
