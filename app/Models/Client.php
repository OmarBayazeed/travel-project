<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'clients';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'social_id',
        'social_type',
    ];


    protected $hidden = [
        'password',
        'social_id',
    ];

    public function cars()
    {
        return $this->hasMany(CarBooking::class, 'client_id');
    }

    public function tours()
    {
        return $this->hasMany(TourBooking::class, 'client_id');
    }

    public function packages()
    {
        return $this->hasMany(PackageBooking::class, 'client_id');
    }

    public function cruises()
    {
        return $this->hasMany(CruiseBooking::class, 'client_id');
    }




    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
