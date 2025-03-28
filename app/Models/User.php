<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Shetabit\Visitor\Traits\Visitor;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes, Visitor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'telephone',
        'cell',
        // 'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function department()
    // {
    //     return $this->belongsTo(Department::class)->withDefault(['name' => 'não informado']);
    // }

    public function userDepartments()
    {
        return $this->hasMany(UserDepartment::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_departments', 'user_id', 'department_id')->whereNull('user_departments.deleted_at');
    }

    public function departmentsName()
    {
        $values = $this->belongsToMany(Department::class, 'user_departments', 'user_id', 'department_id')->whereNull('user_departments.deleted_at')->pluck('name')->toArray();
        if (empty($values) || $values == null) {
            return 'setor não informado ou indefinido';
        }

        return implode(' e ', array_filter(array_merge([implode(', ', array_slice($values, 0, -1))], array_slice($values, -1)), 'strlen'));
    }
}
