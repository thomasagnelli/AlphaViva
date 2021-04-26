<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Lote;

class Reserva extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'clienteId',
        'loteId',
        'status',
        'createdBy',
        'updatedBy',
    ];

    public function cliente()
    {
      return $this->hasOne(Cliente::class, 'id', 'clienteId');
    }

    public function lote()
    {
      return $this->hasOne(Lote::class, 'id', 'loteId');
    }
}
