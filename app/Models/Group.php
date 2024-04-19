<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected array $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'code',
        'user_id',
    ];

    protected $appends = [
        'value',
        'quantity',
    ];

    /** Accessors */

    public function getValueAttribute()
    {
        return 'R$ ' . number_format($this->activeMaterials()->sum('value'), 2, ',', '.');
    }

    public function getQuantityAttribute()
    {
        return $this->activeMaterials()->count();
    }

    /** Relationships */
    public function activeMaterials()
    {
        if (Auth::user()->hasRole('Programador|Administrador')) {
            return $this->hasMany(Material::class, 'group_id', 'id')->where('status', 'Ativo');
        } else {
            return $this->hasMany(Material::class, 'group_id', 'id')->where('department_id', Auth::user()->department_id)->where('status', 'Ativo');
        }
    }
}
