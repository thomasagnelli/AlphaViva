<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lote;
use App\Models\Unidade;

class Quadra extends Model
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
        'lotes',
        'active',
        'unidadeId',
        'createdBy',
        'updatedBy'
    ];

    public function unidade()
    {
        return $this->hasOne(Unidade::class, 'id', 'unidadeId');
    }
    
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'quadraId', 'id');
    }
}
