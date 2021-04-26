<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\UnidadeStatus;

class Unidade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'description',
        'active',
        'createdBy',
        'updatedBy'
    ];

    public function quadras()
    {
        return $this->hasMany(Quadra::class, 'unidadeId');
    }
}
