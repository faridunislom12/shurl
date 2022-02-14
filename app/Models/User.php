<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name',
        'name',
        'middle_name',
        'birthdate',
        'email',
        'phone',
        'address',
        'gender',
        'department_id',
        'job',
        'component_columns',
        'note',
        'is_active',
        'avatar',
        'password',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name', 'role'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'avatar' => 'array',
        'component_columns' => 'array',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */

    /**
     * The password attribute should be hashed
     */
    public function setPasswordAttribute($password)
    {
        if ($password !== null && $password !== "") {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->name . ' ' . $this->middle_name;
    }

    public function getRoleAttribute()
    {
        return $this->roles[0]->name ?? '';
    }


}
