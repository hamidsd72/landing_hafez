<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use App\Models\Gift;
use App\Models\User;

class Code extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function Gift()
    {
        return $this->belongsTo(Gift::class ,'gift_id','id');
    }
    public function user()
    {
        return $this->hasOne(User::class ,'id','user_id');
    }
}
