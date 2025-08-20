<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'channel','event','target','payload','status_code','success','response_body','order_id'
    ];
    protected $casts = ['payload'=>'array','success'=>'bool'];

    public function order(){ return $this->belongsTo(Order::class); }
}
