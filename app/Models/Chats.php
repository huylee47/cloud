<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $fillable = ['staff_id', 'customer_id','guest_id', 'status_id'];

    public function User(){
        return $this->belongsTo(User::class);
    }
    public function Status(){
        return $this->belongsTo(Status::class);
    }
    public function Message(){
        return $this->hasMany(Message::class);
    }
    public function customer(){
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function staff(){
        return $this->belongsTo(User::class, 'staff_id');
    }
    
}
