<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Category;
use App\Models\Gift;

class User extends Authenticatable
{
    use HasApiTokens,HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'mobile', 'password', 'registration', 'account_status', 'mobile_status', 'character'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getProfileAttribute($value)
    {
        if (file_exists($value)) {
            return $value;
        }else{
            return defaultProfilePicture();
        }
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'author_id');
    }
    public function Category()
    {
        return $this->belongsTo(Category::class ,'category_id','id');
    }
    public function gift()
    {
        return $this->belongsTo(Gift::class ,'Gift_id','id');
    }

    public function theme()
    {
        return $this->hasOne('App\Models\Theme');
    }
    public function accountStatusClass()
    {
        switch ($this->account_status){
            case 'active':
                return 'success';
            case 'pending':
                return 'warning';
            case 'rejected' || 'blocked':
                return 'danger';
            default:
                return 'dark';
        }
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }


}
