<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials_view';

    /** Accessors */

    public function getValueAttribute($value)
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
