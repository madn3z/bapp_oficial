<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
//use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    //use HasPermissions;
    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'empresa_id' , 'lastname' ,'rut', 'email', 'password', 'telefono', 'habilitado', 'imagen',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
        

    public function getAllPermissionsAttribute() {
        $permissions = [];
          foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
              $permissions[] = $permission->name;
            }
          }
          return $permissions;
      }
}
