<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use App\Models\Category;

class Gift extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function Category()
    {
        return $this->belongsTo(Category::class ,'category_id','id');
    }

    public function users()
    {
        return $this->hasMany(User::class ,'Gift_id','id');
    }
}
