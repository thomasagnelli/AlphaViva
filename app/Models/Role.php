<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name'
    ];

    const DEFAULT_ROLES = [
      1 => "admin",
      2 => "manager",
      3 => "client",
      4 => "partner",
    ];

    public static function getByName(string $name) : int
    {
      return self::where('name', $name)->first()->id;
    }

    public static function getById(int $id) : ?string
    {
      return self::DEFAULT_ROLES[$id] ?? null;
    }
}