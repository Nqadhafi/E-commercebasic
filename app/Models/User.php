<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name','email','password','phone','role','wa_opt_in'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at'=>'datetime','wa_opt_in'=>'bool'];

    public function addresses(){ return $this->hasMany(CustomerAddress::class); }
    public function orders(){ return $this->hasMany(Order::class); }
    public function cart(){ return $this->hasOne(Cart::class); }

    public function isAdmin(){ return $this->role === 'admin'; }
}
