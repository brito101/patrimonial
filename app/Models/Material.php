<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected array $dates = ['deleted_at'];

    protected $fillable = [
        'registration',
        'secondary_code',
        'serial_number',
        'description',
        'observations',
        'value',
        'group_id',
        'department_id',
        'status',
        'year',
        'user_id',
        'write_off_date_at',
    ];

    /** Accessors */
    public function getValueAttribute($value)
    {
        return 'R$ '.number_format($value, 2, ',', '.');
    }
}
