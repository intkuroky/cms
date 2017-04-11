<?php

namespace App;

use App\Models\CustomerInfo;
use App\Models\RoleUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{

    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function customerInfo()
    {
        return $this->hasOne(CustomerInfo::class, 'user_id', 'id');
    }

    public function roleUser()
    {
        return $this->hasMany(RoleUser::class, 'id', 'id');
    }
}
