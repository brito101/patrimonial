<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials_view';

    protected $appends = ['float_value'];

    /** Accessors */

    public function getValueAttribute($value)
    {
        // Depreciation calculation
        $now  = date('Y');
        $differ = (int) $now - (int) $this->year;
        if ($differ >= 0 && $differ <= 10) {
            $value = $value - ($value * (($differ * 10) / 100));
        } elseif ($differ < 0) {
            $value = $value;
        } else {
            $value = 0;
        }

        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    public function getFloatValueAttribute($value)
    {
        return (float) str_replace(['R$ ', '.', ','], ['', '', '.'], $this->value);
    }
}
