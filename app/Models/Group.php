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
        'depreciated_value',
        'quantity',
    ];

    /** Accessors */
    public function getValueAttribute()
    {
        $total = 0;
        foreach ($this->activeMaterials() as $material) {
            $value = str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $material->value)));
            $total += $value;
        }

        return 'R$ '.number_format($total, 2, ',', '.');
    }

    public function getDepreciatedValueAttribute()
    {
        // Depreciation calculation
        $now = date('Y');
        $total = 0;
        $differ = 0;

        foreach ($this->activeMaterials() as $material) {
            $value = str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $material->value)));

            if ($material->year) {
                $differ = (int) $now - (int) $material->year;
            }

            if ($differ >= 0 && $differ <= 10) {
                $total += $value - ($value * (($differ * 10) / 100));
            } elseif ($differ < 0) {
                $total += $value;
            } else {
                $total += 0;
            }

            if ($total == 0) {
                $total = $value * 10 / 100;
            }
        }

        return 'R$ '.number_format($total, 2, ',', '.');
    }

    public function getQuantityAttribute()
    {
        return $this->activeMaterials()->count();
    }

    /** Relationships */
    private function activeMaterials()
    {
        if (Auth::user()->hasRole('Programador|Administrador')) {
            return Material::where('group_id', $this->id)->where('status', 'Ativo')->get();
        } else {
            return Material::where('group_id', $this->id)->where('department_id', Auth::user()->department_id)->where('status', 'Ativo')->get();
        }
    }
}
